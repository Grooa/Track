<?php

namespace Plugin\Track\Repository;

class VideoRepository
{
    const TABLE_NAME = 'course';

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
