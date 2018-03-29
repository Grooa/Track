<?php

namespace Plugin\Track\Service;


use Plugin\Track\Repository\ModuleRepository;

class ModuleService
{
    private $moduleRepository;

    public function __construct()
    {
        $this->moduleRepository = new ModuleRepository();
    }

    public function findAllPublishedByCourseId(int $courseId): array {
        return $this->moduleRepository->findAllPublishedByCourseId($courseId);
    }
}
