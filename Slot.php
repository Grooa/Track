<?php
namespace Plugin\Track;

class Slot {

    public static function listCourses($params) {
        return ipView('view/slots/courses.php', $params)->render();
    }
}