<?php

namespace Plugin\Track\Widget\CoursePreview;

use Plugin\Track\Repository\CourseRepository;

class Controller extends \Ip\WidgetController
{
    private $courseRepository;

    public function __construct($name, $pluginName, bool $core = false)
    {
        parent::__construct($name, $pluginName, $core);

        $this->courseRepository = new CourseRepository();
    }

    public function generateHtml($revisionId, $widgetId, $data, $skin)
    {
        if (!empty($data['courseId'])) {
            $course = $this->courseRepository->findById($data['courseId']);

            if (!empty($course)) {
                $course = $course->serialize();
            }
        } else {
            $course = null;
        }

        $data['course'] = $course;

        return parent::generateHtml($revisionId, $widgetId, $data, $skin);
    }

}
