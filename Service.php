<?php

namespace Plugin\Track;
use Plugin\Track\Models\TrackModel;

class Service {

    public static function get($trackId = null) {
        return TrackModel::get($trackId);
    }

    public static function findAll() {
        return TrackModel::findAll();
    }
}