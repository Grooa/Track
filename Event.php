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

        if ($data['controller'] == 'AdminController' && $data['action'] == 'courseResources') {
            ipAddJs('assets/courseResources.js', ['async' => 'async', 'defer' => 'defer']);
        }

        ipAddJs('assets/tracks.js', ['async'=>'async', 'defer'=>'defer']);
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
            ipLog()->notice('Track/Event.ipCronExecute()', "Removed incomplete track-orders from storage");
        } else {
            ipLog()->notice('Track/Event.ipCronExecute()', "No incomplete track-orders to remove");
        }
    }
}