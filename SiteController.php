<?php

namespace Plugin\Track;

use Ip\Exception;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Transaction;
use Plugin\Track\Models\AwsS3Model;
use Plugin\Track\Models\TrackModel;

use Plugin\GrooaPayment\Models\PayPalModel;
use Plugin\GrooaPayment\Models\TrackOrderModel;
use Plugin\GrooaPayment\Response\BadRequest;
use Plugin\GrooaPayment\Response\RestError;

class SiteController
{
    public function listTracks()
    {
        $tracks = Service::findAll();

        $layout = new \Ip\Response\Layout(
            ipView('view/list.php', ['tracks' => $tracks])->render());
        $layout->setLayout('track.php');
        //$layout->setLayoutVariable('coverImage', $track['large_thumbnail']);

        return $layout;
    }

    /**
     * @param $trackId
     * @return \Ip\Response\Layout
     * @throws Exception
     */
    public function retrieveTrack($trackId)
    {
        $track = TrackModel::get($trackId);

        if (!$track) {
            throw new Exception('No such Track ' . $trackId);
        }

        $hasPurchased = false;
        $order = null;
        if (ipUser()->loggedIn()) {
            $uid = ipUser()->userId();

            $hasPurchased = TrackOrderModel::hasPurchased($trackId, $uid);
            $order = TrackOrderModel::getByTrackAndUser($trackId, $uid);
        }

        $layout = new \Ip\Response\Layout(
            ipView('view/retrieve.php', [
                'track' => $track,
                'purchasedOn' => !empty($order) && $hasPurchased ?
                    $order['paymentExecuted'] :
                    null,
                'hasPurchased' => $hasPurchased
            ])->render());
        $layout->setLayout('track.php');

        // Set background image
        $layout->setLayoutVariable('coverImage', ipFileUrl('file/repository/' . $track['largeThumbnail']));

        return $layout;
    }

    /**
     * @param $trackId
     * @param $courseId
     * @return \Ip\Response\Layout|\Ip\Response\PageNotFound
     */
    public function retrieveCourse($trackId, $courseId)
    {
        $track = TrackModel::get($trackId, $courseId);

        if (!$track || !$track['course']) {
            return new \Ip\Response\PageNotFound("Cannot find Track of course");
        }

        try {
            TrackProtector::canAccess(ipUser(), $track);
        } catch (Exception $e) {
            // PageAccessControl's layout
            $layout = new \Ip\Response\Layout(ipView('view/forbidden.php',
                [
                    'error' => $e->getMessage()
                ])->render());
            $layout->setLayout('errors.php');
            $layout->setStatusCode(403);
            $layout->setLayoutVariable('title', 'Forbidden');
            $layout->setLayoutVariable('description', "You don't have access to this page");
            return $layout;
        }

        $uri = AwsS3Model::getPresignedUri(
            'courses/videos/Archer.S08E04.Ladyfingers.720p.WEB.x264-[MULVAcoded].mp4');

        $track['course']['video'] = $uri; // Replace the saved url, with the actual AWS S3 url

        $layout = new \Ip\Response\Layout(ipView('view/retrieveCourse.php',
            [
                'track' => $track,
                'course' => $track['course']
            ])->render()
        );
        $layout->setLayout('track.php');

        $layout->setLayoutVariable('coverImage', $track['course']['largeThumbnail']);
        $layout->setLayoutVariable('coverTitle', $track['title']);

        return $layout;
    }

    // TODO:ffl - Move to it's own plugin (GrooaPayment)
    public function createPayment()
    {
        ipRequest()->mustBePost();

        if (!ipUser()->isLoggedIn()) {
            return self::respondForbidden("User not logged in");
        }

        $trackId = ipRequest()->getQuery('track'); // Get referenced track

        if (empty($trackId) || !is_numeric($trackId)) {
            return new BadRequest(['error' => "Missing query `track` or value is non-numeric"]);
        }

        // Prevent a user from double purchasing a track
        if (TrackOrderModel::hasPurchased($trackId, ipUser()->userId())) {
            return new BadRequest(['error' => "You already own this track ($trackId)"]);
        }

        $transaction = self::generateTransactionForTrack($trackId);

        try {
            $payment = PayPalModel::createPayment($transaction);
        } catch (\PayPal\Exception\PayPalConnectionException $pce) {
            return new RestError($pce->getData(), $pce->getCode());
        }

        $id = $payment->getId();

        TrackOrderModel::create([
            'userId' => ipUser()->userId(),
            'trackId' => $trackId,
            'paymentId' => $id
        ]);

        return new \Ip\Response\Json(['paymentID' => $id]);
    }

    public function executePayment()
    {
        ipRequest()->mustBePost();

        if (!ipUser()->isLoggedIn()) {
            return self::respondForbidden("User must be logged in");
        }

        $paymentId = ipRequest()->getPost('paymentID');
        $payerId = ipRequest()->getPost('payerID');

        if (empty($paymentId) || empty($payerId)) {
            return new BadRequest(['error' => 'Missing paymentID or payerID']);
        }

        $trackId = ipRequest()->getQuery('track'); // Get referenced track

        if (empty($trackId) || !is_numeric($trackId)) {
            return new BadRequest(['error' => "Missing query `track` or value is non-numeric"]);
        }

        $uid = ipUser()->userId();
        // Get the order created in createPayment
        $order = TrackOrderModel::getByTrackAndUser($trackId, $uid);

        if (empty($order)) {
            return new BadRequest(['error' => "User has not yet created an order for track: $trackId"]);
        }

        // Expect paymentId created in createPayment to match
        // paymentId gotten from PayPal
        if ($order['paymentId'] != $paymentId) {
            $oldId = $order['paymentId'];
            return new BadRequest([
                'error' => "Stored paymentId: $oldId, does not match paypal's paymentId: $paymentId"
            ]);
        }

        $transaction = self::generateTransactionForTrack($trackId);

        // Do not execute a payment, without a transaction
        if (empty($transaction)) {
            return new BadRequest(['error' => "Cannot find track with id: $trackId"]);
        }

        try {
            $payment = PayPalModel::executePayment($paymentId, $payerId, $transaction);
        } catch (Exception $e) {
            return new RestError(['error' => $e->getMessage()]);
        }

        $sale = $payment->getTransactions()[0]->getRelatedResources()[0]->getSale();

        $saleId = $sale->getId();
        $saleState = $sale->getState();

        // TODO:ffl - Continue here, streamline logic
        // TODO:ffl - Has completed the logic such that a executed payment
        // TODO:ffl - is stored as a pending order in the databse

        if ($saleState == 'pending' || $saleState == 'completed') {
            // Update the table with the extra values to track
            TrackOrderModel::update($order['orderId'], [
                'payerId' => $payerId,
                'saleId' => $saleId,
                'state' => 'pending',
                'paymentExecuted' => date('Y-m-d H:i:s')
            ]);

            if ($saleState == 'completed') {
                // Completes the order, and stores the timestamp
                TrackOrderModel::completeOrder($order['orderId']);
            }
        } else {
            // If it's not completed or pending it could be an error
            TrackOrderModel::update($order['orderId'], ['state' => 'cancelled']);
            return new BadRequest(['error' => "Invalid purchase state: $saleState"]);
        }

        return new \Ip\Response\Json(['saleId' => $saleId, 'state' => $saleState]);
    }

    /**
     * Will find the correct track, and convert it
     * to a PayPal-friendly Transaction object
     *
     * @param int $trackId
     * @return null|\PayPal\Api\Transaction
     */
    private function generateTransactionForTrack($trackId)
    {
        $track = TrackModel::get($trackId);

        if (empty($track)) {
            return null;
        }

        $amount = new Amount();
        $amount->setCurrency('EUR')// We work with Euro
        ->setTotal($track['price']);

        $item = new Item();
        $item->setCurrency('EUR')
            ->setQuantity(1)
            ->setName($track['title'])
            ->setDescription($track['shortDescription'])
            ->setPrice($track['price']);

        $list = new ItemList();
        $list->addItem($item);

        $transaction = new Transaction();
        $transaction
            ->setDescription("Online courses at grooa.com")
            ->setItemList($list)
            ->setAmount($amount);

        return $transaction;
    }


    private static function respondForbidden($msg, $data = [])
    {
        $data['error'] = $msg;
        $forbidden = new \Ip\Response\Json($data);
        $forbidden->setStatusCode(403);

        return $forbidden;
    }

    public function successPayment()
    {

        die(json_encode(ipRequest()->getPost()));
    }

    public function cancelPayment()
    {
        die(json_encode(ipRequest()->getPost()));
    }
}
