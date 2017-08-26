<?php

namespace Plugin\Track\Widget\TrackCard;

use Plugin\Track\Model\Track;

class Controller extends \Ip\WidgetController {

    public function generateHtml($revisionId, $widgetId, $data, $skin)
    {
        if (empty($data['track'])) {
            $data['track'] = [];
        }

        if (!empty($data['trackId'])) {
            $data['track'] = Track::get($data['trackId']);
        }

        return parent::generateHtml($revisionId, $widgetId, $data, $skin);
    }
}