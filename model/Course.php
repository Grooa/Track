<?php

namespace Plugin\Track\Model;

class Course extends AbstractModel
{
    private $label = null;
    private $name = null;
    private $createdOn;
    private $description = null;
    private $cover = null;

    private $modules = [];

    /**
     * @return null|String
     */
    public function getLabel(): ?String
    {
        return $this->label;
    }

    /**
     * @param String $label
     */
    public function setLabel(?String $label)
    {
        $this->label = $label;
    }

    /**
     * @return String
     */
    public function getName(): ?String
    {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName(?String $name)
    {
        $this->name = $name;
    }

    /**
     * @return false|string
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param false|string $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return null|String
     */
    public function getDescription(): ?String
    {
        return $this->description;
    }

    /**
     * @param null|String $description
     */
    public function setDescription(?String $description)
    {
        $this->description = $description;
    }

    /**
     * @return null|String
     */
    public function getCover(): ?String
    {
        return $this->cover;
    }

    /**
     * @param null|String $cover
     */
    public function setCover(?String $cover): void
    {
        $this->cover = $cover;
    }

    /**
     * @return array
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * @param array $modules
     */
    public function setModules(?array $modules): void
    {
        $this->modules = $modules;
    }
}
