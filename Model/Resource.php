<?php

namespace Plugin\Track\Model;

class Resource extends AbstractModel implements Deserializable, Serializable
{


    /**
     * Resource constructor.
     */
    public function __construct()
    {
    }

    public static function deserialize(array $serialized)
    {
        $resource = new Resource();

        return $resource;
    }

    public function serialize(): array
    {
        return ['' => ''];
    }


}
