<?php

namespace Plugin\Track\Service;

use Plugin\Track\Model\Course;
use Plugin\Track\Repository\CourseRepository;

class CourseService
{
    private $courseRepository;

    public function __construct()
    {
        $this->courseRepository = new CourseRepository();
    }

    public function save(Course $course): Course
    {
        return $course;
    }

    public function findByLabel(String $label): ?Course
    {
        return $this->courseRepository->findByLabel($label);
    }

}