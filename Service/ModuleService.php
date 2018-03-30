<?php

namespace Plugin\Track\Service;

use Plugin\Track\Model\Module;
use Plugin\Track\Repository\ModuleRepository;
use Plugin\Track\Repository\VideoRepository;

class ModuleService
{
    private $moduleRepository;
    private $videoRepository;

    private $videoService;

    public function __construct()
    {
        $this->moduleRepository = new ModuleRepository();
        $this->videoRepository = new VideoRepository();
        $this->videoService = new VideoService();
    }

    public function findById(int $id): ?Module {
        $module = $this->moduleRepository->findById($id);

        if (empty($module)) {
            return null;
        }

        $videos = $this->videoService->findByModuleId($module->getId());

        if (!empty($videos)) {
            $module->setVideos($videos);
        }

        return $module;
    }

    public function findAllPublishedByCourseId(int $courseId): array {
        return $this->moduleRepository->findAllPublishedByCourseId($courseId);
    }
}
