<?php

namespace Plugin\Track\Repository;

use Plugin\Track\Model\Module;

class ModuleRepository {

    const TABLE_NAME = 'track';

    /**
     * Finds only the published modules by the one-to-many identifier
     * <em>grooaCourseId</em>.
     *
     * @param int $courseId
     * @return array
     */
    public function findAllPublishedByCourseId(int $courseId): array {
        $rows = ipDb()->selectAll(
            self::TABLE_NAME,
            [
                'trackId',
                'title',
                'shortDescription',
                'longDescription',
                'createdOn',
                'thumbnail',
                'largeThumbnail',
                'price',
                'isFree',
                'order',
                'type',
                'num',
                'state'
            ],
            ['grooaCourseId' => $courseId, 'state' => 'published'],
            'ORDER BY `num` ASC'
        );

        if (!$rows || empty($rows)) {
            return [];
        }

        $modules = array_map(function($row) {
            return self::deserializeModule($row);
        }, $rows);

        return $modules;
    }

    private static function deserializeModule($row) {
        $m = new Module();

        $m->setId($row['trackId']);
        $m->setTitle($row['title']);
        $m->setShortDescription($row['shortDescription']);
        $m->setLongDescription($row['longDescription']);
        $m->setCreatedOn($row['createdOn']);
        $m->setThumbnail($row['thumbnail']);
        $m->setLargeThumbnail($row['largeThumbnail']);
        $m->setPrice($row['price']);
        $m->setIsFree($row['isFree']);
        $m->setType($row['type']);
        $m->setNum($row['num']);
        $m->setState($row['state']);

        return $m;
    }
}
