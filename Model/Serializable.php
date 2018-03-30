<?php

namespace Plugin\Track\Model;

interface Serializable
{
    const IS_SERIALIZABLE = true;

    /**
     * Method to convert a model to a
     * pure assoc-array, which can directly
     * be parsed to json. Ex. through `json_encode()`
     * @return array|null
     */
    public function serialize(): array;
}
