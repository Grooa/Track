<?php

namespace Plugin\Track\Model;

class Course {

    const TABLE = 'course';

    public static function removeVideos($id, $root = 'file/secure') {
        $video = ipDb()->selectRow(Course::TABLE, '`video`', ['courseId' => $id]);

        if (!empty($video) && !empty($video['video']) && $video['video'] != false) {
            return unlink("${root}/${video['video']}");
        }

        return true; // If no file exists, we've implicitly deleted the file
    }

    public static function findVideo($id) {
        $row = ipDb()->selectRow(Course::TABLE, '`video`', ['courseId' => $id]);

        if (!empty($row) && !empty($row['video'])) {
            return $row['video'];
        }

        return '';
    }
}