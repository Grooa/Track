<?php

namespace Plugin\Track\Service;

use Plugin\Track\Model\Video;
use Plugin\Track\Repository\ResourceRepository;
use Plugin\Track\Repository\VideoRepository;

class VideoService
{
    private $videoRepository;
    private $resourceRepository;

    /**
     * VideoService constructor.
     */
    public function __construct()
    {
        $this->videoRepository = new VideoRepository();
        $this->resourceRepository = new ResourceRepository();
    }

    /**
     * Finds the videos connected to a module, and joins
     * it with the available resources
     * TODO:ffl - Use SQL Join to optimalize query
     *
     * @param int $id
     * @return array
     */
    public function findByModuleId(int $id): array {
        $videos = $this->videoRepository->findByModuleId($id);

        if (empty($videos)) {
            return [];
        }

        $videos = array_map(function(Video $v) {
            $resources = $this->resourceRepository->findByVideoId($v->getId());

            $v->setResources($resources);

            return $v;
        }, $videos);

        return $videos;
    }

    public function findByModuleIdWithIdAndTitle(int $moduleId): array {
        return $this->videoRepository->findByModuleIdWithIdAndTitle($moduleId);
    }
}
