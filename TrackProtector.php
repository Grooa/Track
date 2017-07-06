<?php
namespace Plugin\Track;
use Ip\Exception;

/**
 * Validates that the user has bought the selected track
 */
class TrackProtector {

    private static $table = 'track_order';

    public static function canAccess($user, $track) {
        if (empty($track)) {
            return false;
        }

        if (empty($user) || !$user->isLoggedIn()) {
            throw new Exception(null);
        }

        if (!TrackProtector::hasPayed($user, $track)) {
            throw new Exception("You must pay for this course to access it");
        }

        return true;
    }

    /**
     * Ensures a payment is actually registered in
     * our database
     */
    private static function hasPayed($user, $track) {
        $row = ipDb()->selectRow(
            TrackProtector::$table,
            '*',
            ['userId' => $user->userId(), 'trackId' => $track['trackId']]
        );

        return !empty($row);
    }
}