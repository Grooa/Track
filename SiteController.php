<?php
namespace Plugin\Track;

use Ip\Exception;

class SiteController
{

    public function __construct() { }

    public function listTracks() {
        $tracks = Service::findAll();

        return ipView('view/list.php', [ 'tracks' => $tracks ]);
    }

    public function retrieveTrack($trackId) {
        $track = Service::get($trackId);

        if (!$track) {
            throw new Exception('No such Track ' . $trackId);
        }

        $layout = new \Ip\Response\Layout(
            ipView('view/retrieve.php',
            [
                'track' => $track
            ]
        )->render());
        $layout->setLayout('track.php');

        // Set background image
        $layout->setLayoutVariable('coverImage', $track['large_thumbnail']);

        return $layout;
    }

    public function retrieveCourse($trackId, $courseId) {
        $track = Model::get($trackId, $courseId);

        if (!$track || !$track['course']) {
            throw new exception('Cannot find track or course');
        }

        if (!ipUser()->isLoggedIn()) {
            throw new Exception('You must login to view this course');
        }

        // TODO:ffl - Implement 403 Response page
        if (!TrackProtector::canAccess(ipUser(), $track)) {
            throw new Exception("You must pay to access this course");
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

        $layout->setLayoutVariable('coverImage', $track['course']['large_thumbnail']);
        $layout->setLayoutVariable('coverTitle', $track['title']);

        return $layout;
    }
}
