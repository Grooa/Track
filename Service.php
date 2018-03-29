<?php

namespace Plugin\Track;
use Plugin\Track\Model\Module;

class Service {

    public static function get($trackId = null) {
        return Module::get($trackId);
    }

    public static function findAll() {
        return Module::findAll();
    }
}