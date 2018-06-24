<?php

namespace Plugin\Track\Model;

class Course extends AbstractModel implements Deserializable, Serializable
{
    private $label = null;
    private $name = null;
    private $createdOn;
    private $description = null;
    private $introduction = null;
    private $cover = null;

    private $modules = [];

    public function __construct()
    {
        $this->createdOn = date("Y-m-d H:i:s");
    }

    public static function deserialize(array $serialized): ?Course
    {
        $course = new Course();

        if (isset($serialized['id'])) {
            $course->setId($serialized['id']);
        }

        if (isset($serialized['name'])) {
            $course->setName($serialized['name']);
        }

        if (isset($serialized['label'])) {
            $course->setLabel($serialized['label']);
        }

        if (isset($serialized['createdOn'])) {
            $course->setCreatedOn($serialized['createdOn']);
        }

        if (isset($serialized['description'])) {
            $course->setDescription($serialized['description']);
        }

        if (isset($serialized['introduction'])) {
            $course->setIntroduction($serialized['introduction']);
        }

        if (isset($serialized['cover'])) {
            $course->setCover($serialized['cover']);
        }

        if (isset($serialized['modules']) && is_array($serialized['modules'])) {
            if (Module::IS_DESERIALIZABLE) {
                $course->setModules(array_map(function (array $m) {
                    return Module::deserialize($m);
                }, $serialized['modules']));
            }
        }

        return $course;
    }

    public function serialize(): array
    {
        $serialized = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'description' => $this->getDescription(),
            'introduction' => $this->getIntroduction(),
            'cover' => self::createFileUrl($this->getCover()),
            'createdOn' => $this->getCreatedOn(),
            'url' => $this->getUrl()
        ];

        $modules = $this->getModules();
        if (!empty($modules)) {
            if ($modules[0] instanceof Serializable) {
                $serialized['modules'] = array_map(function (Module $module) {
                    return $module->serialize();
                }, $modules);
            }
        }

        return $serialized;
    }

    public function getUrl()
    {
        return ipConfig()->baseUrl() . 'c/' . $this->getLabel();
    }

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
     * @return null|string
     */
    public function getDescription(): ?String
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription(?String $description): void
    {
        $this->description = $description;
    }

    /**
     * @return false|string
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param null|string $createdOn
     */
    public function setCreatedOn(?String $createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return null|String
     */
    public function getIntroduction(): ?String
    {
        return $this->introduction;
    }

    /**
     * @param null|String $introduction
     */
    public function setIntroduction(?String $introduction)
    {
        $this->introduction = $introduction;
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
