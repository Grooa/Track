<?php

namespace Plugin\Track;
use Plugin\Track\Model\Track;

class Service {

    public static function get($trackId = null) {
        return Track::get($trackId);
    }

    public static function findAll() {
        return Track::findAll();
    }
}