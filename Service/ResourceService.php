<?php

namespace Plugin\Track\Service;

use Plugin\Track\Repository\ResourceRepository;

class ResourceService
{
    private $resourceRepository;

    function __construct()
    {
        $this->resourceRepository = new ResourceRepository();
    }

    public function findByVideoId(int $id): array
    {
        return $this->resourceRepository->findByVideoId($id);
    }
}
