<?php


namespace Plugin\Track;

class Model {

    private static
        $trackTable = 'track',
        $courseTable = 'course';

    public static function getTitle() {

    }

    /**
     * Will fetch the tracks bought by the user
     *
     */
    public static function getOrderedTracks($user) {

    }

    /**
     * Will get all the tracks with only the public information
     * (hides information which requires payment)
     */
    public static function getTracks($user = null) {

    }

    public static function findAll() {
        return ipDb()->selectAll(Model::$trackTable, '*', [], "ORDER BY `created` DESC");
    }

    public static function get($trackId) {
        return ipDb()->selectRow('track', '*', ['id' => $trackId]);
    }

    /**
     * Will get the full track data, for
     * a track the user has bought
     */
    public static function getByUser($user) {
        if (!user) { return null; }
    }
}