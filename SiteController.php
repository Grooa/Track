<?php

namespace Plugin\Track;

use Ip\Exception;
use Plugin\Track\Models\AwsS3Model;
use Plugin\Track\Models\PayPalModel;
use Plugin\Track\Models\TrackModel;
use Plugin\Track\Models\TrackOrderModel;

class SiteController
{

    public function __construct()
    {
    }

    public function listTracks()
    {
        $tracks = Service::findAll();

        $layout = new \Ip\Response\Layout(
            ipView('view/list.php', ['tracks' => $tracks])->render());
        $layout->setLayout('track.php');
        //$layout->setLayoutVariable('coverImage', $track['large_thumbnail']);

        return $layout;
    }

    public function retrieveTrack($trackId)
    {
        $track = Service::get($trackId);

        if (!$track) {
            throw new Exception('No such Track ' . $trackId);
        }

        $layout = new \Ip\Response\Layout(
            ipView('view/retrieve.php', ['track' => $track])->render());

        $layout->setLayout('track.php');

        // Set background image
        $layout->setLayoutVariable('coverImage', ipFileUrl('file/repository/' . $track['largeThumbnail']));

        return $layout;
    }

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

        try {
            $payment = PayPalModel::createPayment();
        } catch (\PayPal\Exception\PayPalConnectionException $pce) {
            return new RestResponseError($pce->getData(), $pce->getCode());
        }

        $id = $payment->getId();

//        $intent = $payment->getIntent();
//        $state = $payment->getState();

        return new \Ip\Response\Json(['paymentID' => $id]);
    }

    public function executePayment()
    {
        ipRequest()->mustBePost();

        if (!ipUser()->isLoggedIn()) {
            return self::respondForbidden("User must be logged in");
        }

        $body = ipRequest()->getPost();

        if (empty($body['paymentID']) || empty($body['payerID'])) {
            return new RestBadRequest(['error' => 'Missing paymentID or payerID']);
        }

        try {
            $payment = PayPalModel::executePayment($body);
        } catch(Exception $e) {
            return new RestResponseError(['error' => $e->getMessage()]);
        }

        $sale = $payment->getTransactions()[0]->getRelatedResources()[0]->getSale();

        $saleId = $sale->getId();
        $saleState = $sale->getState();



    }

    private static function respondForbidden ($msg, $data = []) {
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
