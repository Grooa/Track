<?php

namespace Plugin\Track\Repository;

use Plugin\Track\Model\Resource;

class ResourceRepository
{
    const TABLE_NAME = 'course_resource';

    public function findByVideoId(int $id): array {
        $rows = ipDb()->selectAll(
            self::TABLE_NAME,
            [
                'id',
                'label',
                'description',
                'filename',
                'courseId'
            ],
            ['courseId' => $id]
        );

        if (empty($rows)) {
            return [];
        }

        return array_map(function($r) {
            return Resource::deserialize($r);
        }, $rows);
    }

}
