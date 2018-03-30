<?php

$routes['c/{courseLabel}'] = [
    'name' => 'View_course',
    'controller' => 'SiteController',
    'action' => 'viewCoursePage'
];

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

/**
 * REST-endpoints
 */
$routes['api/v1/courses/{label}'] = [
    'name' => 'Course_findByLabel',
    'controller' => 'PublicController',
    'action' => 'findCourseByLabel'
];

$routes['api/v1/modules/{id}'] = [
    'name' => 'Module_findById',
    'controller' => 'PublicController',
    'action' => 'findModuleById'
];

$routes['api/v1/modules/{id}/resources'] = [
    'name' => 'Module_findResourcesByModuleId',
    'controller' => 'PublicController',
    'action' => 'findResourcesByModuleId'
];

$routes['api/v1/videos/{id}/resources'] = [
    'name' => 'Video_findResourcesByVideoId',
    'controller' => 'PublicController',
    'action' => 'findResourcesByVideoId'
];
