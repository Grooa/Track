<?php

namespace Plugin\Track;

class CourseModel {

    const TABLE = 'course';

    public static function removeVideos($id, $root = 'file/secure') {
        $video = ipDb()->selectRow(CourseModel::TABLE, '`video`', ['course_id' => $id]);

        if (!empty($video) && !empty($video['video']) && $video['video'] != false) {
            return unlink("${root}/${video['video']}");
        }

        return true; // If no file exists, we've implicitly deleted the file
    }

    public static function findVideo($id) {
        $row = ipDb()->selectRow(CourseModel::TABLE, '`video`', ['course_id' => $id]);

        if (!empty($row) && !empty($row['video'])) {
            return $row['video'];
        }

        return '';
    }
}