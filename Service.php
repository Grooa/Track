<?php

namespace Plugin\Track;

class Service {

    public static function get($trackId = null) {
        return Model::get($trackId);
    }

    public static function findAll() {
        return Model::findAll();
    }
}