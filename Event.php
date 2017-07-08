<?php
/**
 * @package   ImpressPages
 */
namespace Plugin\Track;


class Event
{
    public static function ipBeforeController()
    {
        ipAddJs('assets/tracks.js');
        ipAddCss('assets/tracks.css');
    }

}