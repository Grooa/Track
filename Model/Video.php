<?php

namespace Plugin\Track\Model;

class Video extends AbstractModel
{
    private $title = null;
    private $shortDescription = null;
    private $longDescription = null;
    private $createdOn;
    private $thumbnail = null;
    private $largeThumbnail = null;
    private $price = 0.0;
    private $url = null;
    private $moduleId = null;

    public function __construct()
    {
        $this->createdOn = date("Y-m-d H:i:s");
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
    public function getLargeThumbnail(): ?String
    {
        return $this->largeThumbnail;
    }

    /**
     * @param null|string $largeThumbnail
     */
    public function setLargeThumbnail(?String $largeThumbnail): void
    {
        $this->largeThumbnail = $largeThumbnail;
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

}
