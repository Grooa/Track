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

    public function findByLabel(String $label): ?Course
    {
        $row = ipDb()->selectRow(
            self::TABLE_NAME,
            ['id', 'name', 'createdOn', 'label', 'description', 'cover'],
            ['label' => $label]
        );

        if (!$row || empty($row['id'])) {
            return null;
        }

        $course = new Course();
        $course->setId($row['id']);
        $course->setLabel($row['label']);
        $course->setName($row['name']);
        $course->setDescription($row['description']);
        $course->setCreatedOn($row['createdOn']);
        $course->setCover($row['cover']);

        return $course;
    }
}