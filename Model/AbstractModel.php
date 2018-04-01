<?php

namespace Plugin\Track\Model;

abstract class AbstractModel
{
    protected $id = -1;

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    protected static function createFileUrl(?String $filename): ?String {
        if (empty($filename)) {
            return null;
        }

        return ipFileUrl("file/repository/$filename");
    }
}
