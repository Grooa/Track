<?php

/**
 * Route to display all available tracks
 * one can buy
 */
//$routes['tracks'] = [
//    'name' => 'Track_list',
//    'controller' => 'SiteController',
//    'action' => 'listTracks'
//];

/**
 * Will display a specific track.
 * Information displayed should be based,
 * on the users status (logged_in & bought_track)
 */
$routes['tracks/{trackId}'] = [
    'where' => [
        'trackId' => '\d+'
    ],
    'name' => 'Track_retrieve',
    'controller' => 'SiteController',
    'action' => 'retrieveTrack'
];

/**
 * Sends the user to the specific course site,
 * where videos and other information are available.
 * Page requires a user, and that the track is purchased
 */
// TODO:ffl - The url does for some reason not catch a route
// TODO:ffl - dafuq is this shit...
$routes['tracks/{trackId}/course/{courseId}'] = [
    'where' => [
        'trackId' => '\d+',
        'courseId' => '\d+'
    ],
    'name' => 'Course_retrieve',
    'controller' => 'SiteController',
    'action' => 'retrieveCourse'
];

$routes['tracks/{trackId}/course/{courseId}/resources'] = [
    'where' => [
        'trackId' => '\d+',
        'courseId' => '\d+'
    ],
    'name' => 'Course_resource_retrieve',
    'controller' => 'SiteController',
    'action' => 'retrieveCourseResources'
];


