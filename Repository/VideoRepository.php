<?php

namespace Plugin\Track\Repository;

use Plugin\Track\Model\Video;

class VideoRepository
{
    const TABLE_NAME = 'course';

    public function findByModuleId(int $moduleId): array {
        $rows = ipDb()->selectAll(
            self::TABLE_NAME,
            [
                'courseId',
                'title',
                'shortDescription',
                'longDescription',
                'createdOn',
                'thumbnail',
                'largeThumbnail',
                'price',
                'video',
                'trackId'
            ],
            ['trackId' => $moduleId],
            'ORDER BY `createdOn` ASC'
        );

        if (empty($rows)) {
            return [];
        }

        return array_map(function($r) {
            return Video::deserialize($r);
        }, $rows);
    }

    public function findByModuleIdWithIdAndTitle(int $moduleId): array
    {
        $rows = ipDb()->selectAll(
            self::TABLE_NAME,
            '`courseId`, `title`',
            ['trackId' => $moduleId],
            "ORDER BY `createdOn` ASC");

        return array_map(function ($r) {
            return [intval($r['courseId']), $r['title']];
        }, $rows);
    }
}
