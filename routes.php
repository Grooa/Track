<?php

$routes['online-courses/contact'] = [
    'name' => 'Contact_sales',
    'controller' => 'SiteController',
    'action' => 'contactSales'
];

/**
 * Will display a specific track.
 * Information displayed should be based,
 * on the users status (logged_in & bought_track)
 */
$routes['online-courses/{trackId}'] = [
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
//$routes['online-courses/{trackId}/course/{courseId}'] = [
//    'where' => [
//        'trackId' => '\d+',
//        'courseId' => '\d+'
//    ],
//    'name' => 'Course_retrieve',
//    'controller' => 'SiteController',
//    'action' => 'retrieveCourse'
//];

$routes['online-courses/{trackId}/v/{courseId}'] = [
    'where' => [
        'trackId' => '\d+',
        'courseId' => '\d+'
    ],
    'name' => 'Course_retrieve',
    'controller' => 'SiteController',
    'action' => 'retrieveCourse'
];

$routes['online-courses/{trackId}/v/{courseId}/resources'] = [
    'where' => [
        'trackId' => '\d+',
        'courseId' => '\d+'
    ],
    'name' => 'Course_resource_retrieve',
    'controller' => 'PublicController',
    'action' => 'retrieveCourseResources'
];


