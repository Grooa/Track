<?php

namespace Plugin\Track\Service;

use Plugin\Track\Repository\VideoRepository;

class VideoService
{
    private $videoRepository;

    /**
     * VideoService constructor.
     */
    public function __construct()
    {
        $this->videoRepository = new VideoRepository();
    }

    public function findByModuleIdWithIdAndTitle(int $moduleId): array {
        return $this->videoRepository->findByModuleIdWithIdAndTitle($moduleId);
    }
}
