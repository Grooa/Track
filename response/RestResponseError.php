<?php
namespace Plugin\Track;

class RestResponseError extends \Ip\Response\Json {

    /**
     * @param mixed $data
     * @param int $statusCode
     */
    public function __construct($data, $statusCode = 500)
    {
        parent::__construct($data);
        parent::setStatusCode($statusCode);
    }


}