<?php
namespace Plugin\Track;

use Plugin\GrooaPayment\Model\TrackOrder;
use Plugin\Track\Model\Module;

/**
 * Validates that the user has bought the selected track
 */
class TrackProtector {

    /**
     * Ensures 
     * @param \Ip\User $user
     * @param $trackId
     * @return bool
     */
    public static function canAccess($user, $trackId) {
        if (empty($trackId)) {
            return false;
        }

        if (empty($user) || !$user->isLoggedIn()) {
            return false;
        }

        if (!TrackOrder::hasPurchased($trackId, $user->userId())) {
            return false;
        }

        return true;
    }

}