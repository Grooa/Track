<?php

namespace Plugin\Track\Model;

class Track {

    public static
        $trackTable = 'track',
        $courseTable = 'course';

    const TABLE = 'track';

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
        return ipDb()->selectAll(Track::$trackTable, '*', [], "ORDER BY `createdOn` DESC");
    }

    public static function get($trackId, $courseId = null) {
        $track = ipDb()->selectRow(Track::$trackTable, '*', ['trackId' => $trackId]);

        if ($courseId == null) {
            $track['courses'] = ipDb()->selectAll(
                Track::$courseTable,
                '`courseId`, `title`, `shortDescription`, `thumbnail`',
                ['trackId' => $trackId]
            );
        } else {
            $track['course'] = ipDb()->selectRow(
                Track::$courseTable,
                '*',
                ['trackId' => $trackId, 'courseId' => $courseId]
            );
        }

        return $track;
    }

    /**
     * Will fetch a list with all the records form the database,
     * in the following format:
     * [
     *  [0, 'Some Title']
     * ]
     *
     * Where 0 is `track_id` and 'Some Title' is `title`
     */
    public static function findWithIdAndTitle() {
        $tracks = ipDb()->selectAll('track', '`trackId`, `title`', [], "ORDER BY `createdOn` DESC");

        return array_map(function($t) {
            return [$t['trackId'], $t['title']];
        }, $tracks);
    }

    /**
     * Will get the full track data, for
     * a track the user has bought
     */
    public static function getByUser($user) {
        if (!user) { return null; }
    }
}