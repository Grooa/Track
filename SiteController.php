<?php

namespace Plugin\Track;

use Ip\Exception;
use Plugin\GrooaPayment\Models\PayPalModel;
use Plugin\Track\Models\AwsS3Model;
use Plugin\Track\Models\TrackModel;

use Plugin\GrooaPayment\Models\TrackOrderModel;

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
                'hasPurchased' => $hasPurchased,
                'payPalCheckout' =>
                    ipUser()->isLoggedIn() && !$hasPurchased ?
                        PayPalModel::getCheckoutView($trackId) :
                        null
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
}
