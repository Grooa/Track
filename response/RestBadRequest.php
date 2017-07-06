<?php
namespace Plugin\Track;

class RestBadRequest extends \Ip\Response\Json {

    public function __construct($data)
    {
        parent::__construct($data);
        parent::setStatusCode(400);
    }

    public function setStatusCode($code)
    {
        return parent::setStatusCode(400); // Bad request is always 400
    }


}