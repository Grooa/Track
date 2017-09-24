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
        $params['tracks'] = Track::findAllPublished();
        return ipView('view/slots/tracks.php', $params)->render();
    }

    public static function Track_userTracks($params)
    {
        if (empty($params['userId'])) {
            return 'You must login to access your courses';
        }

        $courseId = null;

        if (!empty($params['grooaCourse'])) {
            $course = Track::getGrooaCourseByLabel($params['grooaCourse']);
            $courseId = !empty($course) ? $course['id'] : $courseId;
        }

        $purchasedTracks = TrackOrder::getByUserId($params['userId'], $courseId);

        $params['tracks'] = $purchasedTracks;
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