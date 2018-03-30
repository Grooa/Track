<?php
/**
 * @package   ImpressPages
 */
namespace Plugin\Track;

use Plugin\GrooaPayment\Model\TrackOrder;

class Event
{
    public static function ipBeforeController($data)
    {
        ipAddCss('assets/tracks.css');

        if ($data['action'] == 'retrieveCourse' && $data['plugin'] == 'Track') {
//            ipAddJs('assets/courseResources.js');
        }

        ipAddJs('assets/tracks.js', ['async'=>'async', 'defer'=>'defer']);

        if (ipConfig()->isDevelopmentEnvironment()) {
            ipAddJs('assets/dist/bundle.js', ['defer' => 'defer']);
        } else {
            ipAddJs('assets/dist/bundle.min.js', ['defer' => 'defer']);
        }

    }


    /**
     * Clear objects which
     */
    public static function ipCronExecute() {
        $success = false;
        try {
            $success = TrackOrder::clearUnresolvedOrders();
        } catch(\Ip\DbException $e) {
            // Ignore Exception
        }

        if ($success) {
            ipLog()->notice('Track/Event.ipCronExecute()', ['message' => "Removed incomplete track-orders from storage"]);
        } else {
            ipLog()->notice('Track/Event.ipCronExecute()', ['error' => "No incomplete track-orders to remove"]);
        }
    }
}
