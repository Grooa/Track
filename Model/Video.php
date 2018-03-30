<?php

namespace Plugin\Track\Model;

class Video extends AbstractModel implements Deserializable, Serializable
{
    private $title = null;
    private $shortDescription = null;
    private $longDescription = null;
    private $createdOn;
    private $thumbnail = null;
    private $cover = null;
    private $price = 0.0;
    private $url = null;
    private $moduleId = null;

    private $resources = [];

    public function __construct()
    {
        $this->createdOn = date("Y-m-d H:i:s");
    }

    public function serialize(): array
    {
        $serialized = [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'shortDescription' => $this->getShortDescription(),
            'longDescription' => $this->getLongDescription(),
            'createdOn' => $this->getCreatedOn(),
            'thumbnail' => $this->getThumbnail(),
            'cover' => $this->getCover(),
            'price' => $this->getPrice(),
            'url' => $this->getUrl(),
            'moduleId' => $this->getModuleId()
        ];

        if (!empty($this->getResources())) {
            $resources = $this->getResources();

            if ($resources[0] instanceof Serializable) {
                $serialized['resources'] = array_map(function(Resource $r) {
                    return $r->serialize();
                }, $resources);
            }
        }

        return $serialized;
    }

    public static function deserialize(array $serialized): ?Video
    {
        $video = new Video();

        if (isset($serialized['courseId'])) {
            $video->setId($serialized['courseId']);
        }

        if (isset($serialized['title'])) {
            $video->setTitle($serialized['title']);
        }

        if (isset($serialized['shortDescription'])) {
            $video->setShortDescription($serialized['shortDescription']);
        }

        if (isset($serialized['longDescription'])) {
            $video->setLongDescription($serialized['longDescription']);
        }

        if (isset($serialized['createdOn'])) {
            $video->setCreatedOn($serialized['createdOn']);
        }

        if (isset($serialized['thumbnail'])) {
            $video->setThumbnail($serialized['thumbnail']);
        }

        if (isset($serialized['largeThumbnail'])) {
            $video->setCover($serialized['largeThumbnail']);
        }

        if (isset($serialized['price'])) {
            $video->setPrice($serialized['price']);
        }

        // TODO:ffl - Rename the DB-field video to url
        if (isset($serialized['video'])) {
            $video->setUrl($serialized['video']);
        }

        // TODO:ffl - Rename the DB-foreign-key to moduleId
        if (isset($serialized['trackId'])) {
            $video->setModuleId($serialized['trackId']);
        }

        if (isset($serialized['resources']) && is_array($serialized['resources'])) {
            $resources = $serialized['resources'];

            if (Resource::IS_DESERIALIZABLE) {
                $video->setResources(array_map(function($r) {
                    return Resource::deserialize($r);
                }, $resources));
            }
        }

        return $video;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?String
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     */
    public function setTitle(?String $title): void
    {
        $this->title = $title;
    }

    /**
     * @return null|string
     */
    public function getShortDescription(): ?String
    {
        return $this->shortDescription;
    }

    /**
     * @param null|string $shortDescription
     */
    public function setShortDescription(?String $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * @return null|string
     */
    public function getLongDescription(): ?String
    {
        return $this->longDescription;
    }

    /**
     * @param null|string $longDescription
     */
    public function setLongDescription(?String $longDescription): void
    {
        $this->longDescription = $longDescription;
    }

    /**
     * @return null|string
     */
    public function getCreatedOn(): ?String
    {
        return $this->createdOn;
    }

    /**
     * @param null|string $createdOn
     */
    public function setCreatedOn(?String $createdOn): void
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return null|String
     */
    public function getThumbnail(): ?String
    {
        return $this->thumbnail;
    }

    /**
     * @param null|string $thumbnail
     */
    public function setThumbnail(?String $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @return null|string
     */
    public function getCover(): ?String
    {
        return $this->cover;
    }

    /**
     * @param null|string $cover
     */
    public function setCover(?String $cover): void
    {
        $this->cover = $cover;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return null|string
     */
    public function getUrl(): ?String
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     */
    public function setUrl(?String $url): void
    {
        $this->url = $url;
    }

    /**
     * @return null|int
     */
    public function getModuleId(): ?int
    {
        return $this->moduleId;
    }

    /**
     * @param null|int $moduleId
     */
    public function setModuleId(?int $moduleId): void
    {
        $this->moduleId = $moduleId;
    }

    /**
     * @return array
     */
    public function getResources(): array
    {
        return $this->resources;
    }

    /**
     * @param array $resources
     */
    public function setResources(array $resources): void
    {
        $this->resources = $resources;
    }

}
