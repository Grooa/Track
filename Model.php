<?php


namespace Plugin\Track;

class Model {

    public static
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

    public static function get($trackId, $courseId = null) {
        $track = ipDb()->selectRow(Model::$trackTable, '*', ['track_id' => $trackId]);

        if ($courseId == null) {
            $track['courses'] = ipDb()->selectAll(
                Model::$courseTable,
                '`course_id`, `title`, `short_description`, `thumbnail`',
                ['track_id' => $trackId]
            );
        } else {
            $track['course'] = ipDb()->selectRow(
                Model::$courseTable,
                '*',
                ['track_id' => $trackId, 'course_id' => $courseId]
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
        $tracks = ipDb()->selectAll('track', '`track_id`, `title`', [], "ORDER BY `created` DESC");

        return array_map(function($t) {
            return [$t['track_id'], $t['title']];
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