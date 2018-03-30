<?php

namespace Plugin\Track\Model;

interface Deserializable
{
    /**
     * Property to help decide is a class
     * is serializable, as we cannot properly use
     * `instanceof` when deserializing
     * @var bool
     */
    const IS_DESERIALIZABLE = true;

    /**
     * @param array $serialized The serialized value, in assoc-array
     * @return mixed|null
     */
    public static function deserialize(array $serialized);
}
