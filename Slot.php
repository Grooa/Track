<?php

namespace Plugin\Track;

use Plugin\GrooaPayment\Model\TrackOrder;
use Plugin\Track\Model\Track;

class Slot
{

    public static function Track_listCourses($params)
    {
        return ipView('view/slots/courses.php', $params)->render();
    }

    public static function Track_listTracks($params)
    {
        $courseId = null;

        if (!empty($params['course'])) {
            $course = Track::getGrooaCourseByLabel($params['course']);
            $courseId = !empty($course) ? $course['id'] : null;
        }

        $params['tracks'] = Track::findAllPublished($courseId);
        return ipView('view/slots/tracks.php', $params)->render();
    }

    public static function Track_userTracks($params)
    {
        if (empty($params['userId'])) {
            $params['error'] = 'You must login to access your courses';
            return ipView('view/slots/tracks.php', $params)->render();
        }

        $courseId = null;

        if (!empty($params['grooaCourse'])) {
            $course = Track::getGrooaCourseByLabel($params['grooaCourse']);
            $courseId = !empty($course) ? $course['id'] : $courseId;
        }

        $purchasedTracks = TrackOrder::getByUserId($params['userId'], $courseId);

        if (!empty($purchasedTracks)) {
            $params['tracks'] = $purchasedTracks;
        } else {
            $params['error'] = "You don't have access to any Modules";
        }

        $params['hasPurchased'] = true;
        return ipView('view/slots/tracks.php', $params)->render();
    }

    public static function Track_newestTracks($params)
    {
        $params['tracks'] = Track::getAllLastCreated(2);
//        $params['showCreatedOn'] = true;
        return ipView('view/slots/tracks.php', $params)->render();
    }
}