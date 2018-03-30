<?php

namespace Plugin\Track\Repository;

use Plugin\Track\Model\Course;

class CourseRepository
{
    const TABLE_NAME = 'grooa_course';

    public function save(Course $course): Course
    {
        return $course;
    }

    public function findById(int $id): ?Course
    {
        $row = ipDb()->selectRow(
            self::TABLE_NAME,
            [
                'id',
                'name',
                'createdOn',
                'label',
                'description',
                'introduction',
                'cover'
            ],
            ['id' => $id]
        );

        if (empty($row)) {
            return null;
        }

        return Course::deserialize($row);
    }

    public function findByLabel(String $label): ?Course
    {
        $row = ipDb()->selectRow(
            self::TABLE_NAME,
            ['id', 'name', 'createdOn', 'label', 'description', 'introduction', 'cover'],
            ['label' => $label]
        );

        if (!$row || empty($row['id'])) {
            return null;
        }

        return Course::deserialize($row);
    }

    public function countByLabel(String $label): int
    {
        $row = ipDb()->fetchRow("SELECT COUNT(*) AS c FROM " . ipTable(self::TABLE_NAME) . " WHERE `label`=?", [
            $label
        ]);

        return (int)$row['c'];
    }
}
