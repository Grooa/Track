<?php
namespace Plugin\Track\Model;

/**
 * Note the actual file, is stored in an AWS S3 Bucket
 */
class TrackResource {

    const TABLE = "course_resource";

    public static function getAll($trackId, $courseId) {
        return ipDb()->selectAll('course_resource', '*', [
            'trackId' => $trackId,
            'courseId' => $courseId
        ]);
    }

    public static function get($trackId, $courseId, $resourceId) {

    }

    public static function add($trackId, $courseId, $name) {

    }
}