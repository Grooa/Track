<?php

namespace Plugin\Track\Model;

use Ip\Exception;

class Track {

    const TABLE = 'track';
    const GROOA_COURSE_TABLE = 'grooa_course';

    public static function findAllPublished ($courseId = null) {
        return self::findAll('published', $courseId);
    }

    public static function findAll($state = null, $courseId = null) {
        $were = [];

        if (!empty($state)) {
            if (!in_array($state, ['draft', 'published', 'withdrawn'])) {
                throw new Exception("Unknown Track state: $state");
            }

            $were['state'] = $state;
        }

        if (!empty($courseId)) {
            $were['grooaCourseId'] = $courseId;
        }

        return ipDb()->selectAll(self::TABLE, '*', $were, 'ORDER BY `num` ASC');
    }

    public static function isFree($trackId) {
        return !empty(ipDb()->selectRow(self::TABLE, 'trackId', ['trackId' => $trackId, 'isFree' => true]));
    }

    public static function get($trackId, $courseId = null) {
        if (empty($trackId)) {
            return null;
        }

        $track = ipDb()->selectRow(self::TABLE, '*', ['trackId' => $trackId]);

        if (empty($track)) {
            return null;
        }

        $track['grooaCourse'] = self::getGrooaCourseById($track['grooaCourseId']);
        $track['courseRootUri'] = $track['grooaCourse']['label'];

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

    public static function getGrooaCourseIdByTrackId($trackId) {
        $row = ipDb()->selectRow(self::TABLE, ['grooaCourseId'], ['trackId' => $trackId]);

        return !empty($row) && !empty($row['grooaCourseId']) ? $row['grooaCourseId'] : null;
    }

    public static function getGrooaCourseByLabel($label) {
        return ipDb()->selectRow(self::GROOA_COURSE_TABLE, '*', ['label' => $label]);
    }

    public static function getGrooaCourseById($id) {
        return ipDb()->selectRow(self::GROOA_COURSE_TABLE, '*', ['id' => $id]);
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

    public static function getGrooaCourseWithIdAndName() {
        $courses = ipDb()->selectAll(self::GROOA_COURSE_TABLE, ['id', 'name'], [], 'ORDER BY `name` DESC');

        return array_map(function($t) {
            return [$t['id'], $t['name']];
        }, $courses);
    }

    /**
     * Will get the full track data, for
     * a track the user has bought
     */
    public static function getByUser($user) {
        if (!user) { return null; }
    }
}