<?php

namespace Plugin\Track\Model;

class Resource extends AbstractModel implements Deserializable, Serializable
{
    private $label = null;
    private $description = null;
    private $filename = null;
    private $videoId = null;
    private $moduleId = null;

    private $url = null;

    /**
     * Resource constructor.
     */
    public function __construct()
    {
    }

    public static function deserialize(array $serialized)
    {
        $resource = new Resource();

        if (isset($serialized['id'])) {
            $resource->setId($serialized['id']);
        }

        if (isset($serialized['label'])) {
            $resource->setLabel($serialized['label']);
        }

        if (isset($serialized['description'])) {
            $resource->setDescription($serialized['description']);
        }

        if (isset($serialized['filename'])) {
            $resource->setFilename($serialized['filename']);
        }

        // TODO:ffl - Rename DB-field to videoId
        if (isset($serialized['courseId'])) {
            $resource->setVideoId($serialized['courseId']);
        }

        // TODO:ffl - This field is redundant in the DB, and should be removed
        if (isset($serialized['moduleId'])) {
            $resource->setModuleId($serialized['moduleId']);
        }

        return $resource;
    }

    public function serialize(): array
    {
        $serialized = [
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'description' => $this->getDescription(),
            'filename' => $this->getFilename(),
            'videoId' => $this->getVideoId(),
            'url' => $this->getUrl()
            // Note we ignore moduleId, as it is redundant
        ];

        return $serialized;
    }

    /**
     * @return null
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param null $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }

    /**
     * @return null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return null
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param null $filename
     */
    public function setFilename($filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return null
     */
    public function getVideoId()
    {
        return $this->videoId;
    }

    /**
     * @param null $videoId
     */
    public function setVideoId($videoId): void
    {
        $this->videoId = $videoId;
    }

    /**
     * @return null
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * @param null $moduleId
     */
    public function setModuleId($moduleId): void
    {
        $this->moduleId = $moduleId;
    }

    /**
     * @return null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param null $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }
}
