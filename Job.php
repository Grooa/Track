<?php

namespace Plugin\Track;

use Plugin\Track\Model\Module;

class Job
{
    /**
     * Expect path: online-courses/<trackId>/v/<courseId>,
     * Everything else is automatically approved.
     *
     * If a track is found, then ensure the user actually has purchased the course
     */
    public static function ipRouteAction($info)
    {
        $params = self::parseTrackReqParams($info['relativeUri']);

        if (empty($params)) {
            return null;
        }

        // Let a track which doesn't exists fall through,
        // and give a 404 instead
        if (empty($params['online-courses']) || !self::trackExists($params['online-courses'])) {
            return null;
        }

        if (empty($params) || TrackProtector::canAccess(ipUser(), $params['online-courses'])) {
            return null;
        }

        return array(
            'plugin' => 'PageAccessControl',
            'controller' => 'PublicController',
            'action' => 'forbidden'
        );
    }

    /**
     * Ensures that a track exists.
     * A track which doesn't exists, should not
     * give a 403 error, but 404 instead.
     *
     * @param int $trackId
     * @return bool
     */
    private static function trackExists($trackId) {
        return !empty(Module::get($trackId));
    }

    /**
     * Parses the uri from the following map
     * `tracks/<trackId:number>/course/<courseId:number>
     * @param string $uri
     * @return array|null
     */
    private static function parseTrackReqParams($uri) {
        $paths = explode("/", $uri);

        if ((count($paths) < 4) || ($paths[0] != 'online-courses') || ($paths[2] != 'v')) {
            return null;
        }

        // trackId and courseId must be numeric
        if (!is_numeric($paths[1]) || !is_numeric($paths[3])) {
            return null;
        }

        return [
            $paths[0] => $paths[1],
            $paths[2] => $paths[3]
        ];
    }

}