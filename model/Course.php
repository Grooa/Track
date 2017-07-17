<?php

namespace Plugin\Track\Model;

class Course
{
    const TABLE = 'course';

    /**
     * @deprecated Uses Amazon Web Services S3 instead (see Plugin\Track\Model\AwsS3)
     */
    public static function removeVideos($id, $root = 'file/secure')
    {
        $video = ipDb()->selectRow(Course::TABLE, '`video`', ['courseId' => $id]);

        if (!empty($video) && !empty($video['video']) && $video['video'] != false) {
            return unlink("${root}/${video['video']}");
        }

        return true; // If no file exists, we've implicitly deleted the file
    }

    /**
     * @deprecated Uses Amazon Web Services S3 instead
     */
    public static function findVideo($id)
    {
        $row = ipDb()->selectRow(Course::TABLE, '`video`', ['courseId' => $id]);

        if (!empty($row) && !empty($row['video'])) {
            return $row['video'];
        }

        return '';
    }

    /**
     * Will create a HTML-select-friendly list
     */
    public static function getWithIdAndTitle($trackId)
    {
        $rows = ipDb()->selectAll(
            Course::TABLE,
            '`courseId`, `title`',
            ['trackId' => $trackId],
            "ORDER BY `createdOn` ASC");

        return array_map(function ($r) {
            return [intval($r['courseId']), $r['title']];
        }, $rows);
    }
}