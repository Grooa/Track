<?php
namespace Plugin\Track\Models;

use Ip\Exception;

class TrackOrderModel {

    const TABLE = 'track_order';

    public static function get($query) {
        return ipDb()->selectRow(self::TABLE, '*', $query);
    }

    public static function getAll($query) {
        return ipDb()->selectAll(self::TABLE, '*', $query);
    }

    public static function getBySaleId($id) {
        return self::get(['saleId' => $id]);
    }

    public static function getByPaymentId($id) {
        return self::getAll(['paymentId' => $id]);
    }

    public static function create($fields) {
        if (!empty($field['orderId'])) {
            unset($field['orderId']);
        }

        if (empty($fields['type'])) {
            $fields['type'] = 'paypal'; // Treat paypal as default value
        }

        if (!empty($fields['createdOn'])) {
            unset($fields['createdOn']);
        }

        if (empty($fields['userId']) || empty($fields['trackId'])) {
            throw new Exception("Missing required field `userId` or `trackId`");
        }

        return ipDb()->insert(self::TABLE, $fields);
    }

    /**
     * Will set the order to `completed`, and set it's timestamp
     * @param int $orderId
     */
    public static function completeOrder($orderId) {

    }

    public static function cancelOrder($orderId) {

    }

    /**
     * Called when payment has been executed,
     * but not necessarily confirmed
     *
     * @param int $orderId
     * @param string $paymentId
     * @param string $payerId
     * @param string $saleId
     * @param string $state
     */
    public static function executePayment($orderId, $paymentId, $payerId, $saleId, $state = "pending") {

    }
}