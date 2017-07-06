<?php

namespace Plugin\Track;

class Config
{
    private static $config;

    public static function getPayPal() {
        return Config::$config['paypal'];
    }

    public static function getAws() {
        return Config::$config['aws'];
    }

    public static function init()
    {
        Config::$config = include '_config.php';
    }
}
Config::init();