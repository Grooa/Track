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
        //ipAddJs('https://www.paypalobjects.com/api/checkout.js');
        ipAddCss('assets/tracks.css');
    }

}