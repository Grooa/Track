<?php

namespace Plugin\Track\Model;

class Track {

    const TABLE = 'track';

    public static function findAll() {
        return ipDb()->selectAll(self::TABLE, '*', [], "ORDER BY `createdOn` DESC");
    }

    public static function get($trackId, $courseId = null) {
        $track = ipDb()->selectRow(self::TABLE, '*', ['trackId' => $trackId]);

        if (empty($track)) {
            return null;
        }

        if ($courseId == null) {
            $track['courses'] = ipDb()->selectAll(
                Course::TABLE,
                '`courseId`, `title`, `shortDescription`, `thumbnail`',
                ['trackId' => $trackId]
            );
        } else {
            $track['course'] = ipDb()->selectRow(
                Course::TABLE,
                '*',
                ['trackId' => $trackId, 'courseId' => $courseId]
            );
        }

        return $track;
    }

    public static function getAllLastCreated($limit = 10) {
        return ipDb()->selectAll(self::TABLE, '*', [], "ORDER BY `createdOn` DESC LIMIT " . esc($limit) . ";");
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
    public static function getWithIdAndTitle() {
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