<?php

namespace Plugin\Track;

use Plugin\GrooaPayment\Response\RestError;
use Plugin\Track\Model\Course;

class PublicController
{

    /**
     * Retrieves a list of courses for the selected track.
     *
     * format: [
     *      [<courseId:int>, <title:string>]
     * ]
     */
    public function listCourses()
    {
        if (empty(ipAdminId()) || ipAdminId() < 0) {
            return new RestError('Not Authorized', 403);
        }

        $trackId = ipRequest()->getQuery('trackId');

        if (empty($trackId) || $trackId < 0) {
            return new RestError('Missing query-param `trackId`', 400);
        }

        $courses = Course::getWithIdAndTitle($trackId);

        return new \Ip\Response\Json($courses);
    }
}