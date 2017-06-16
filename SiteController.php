<?php

namespace Plugin\Track;

use Ip\Exception;

class SiteController
{
    public function listTracks() {
        $tracks = Service::findAll();

        return ipView('view/list.php', [ 'tracks' => $tracks ]);
    }

    public function retrieveTrack($trackId) {
        $track = Service::get($trackId);

        if (!$track) {
            throw new Exception('No such Track ' . $trackId);
        }

        return ipView('view/retrieve.php', ['track' => $track]);
    }

}
