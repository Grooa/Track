<?php

/**
 * Route to display all available tracks
 * one can buy
 */
$routes['tracks'] = [
    'name' => 'Track_list',
    'controller' => 'SiteController',
    'action' => 'listTracks'
];

$routes['paypal/create-payment'] = [
    'name' => 'Track_createPayment',
    'controller' => 'SiteController',
    'action' => 'createPayment'
];

$routes['paypal/execute-payment'] = [
    'name' => 'Track_executePayment',
    'controller' => 'SiteController',
    'action' => 'executePayment'
];

$routes['paypal/success-payment'] = [
    'name' => 'Track_paymentSuccess',
    'controller' => 'SiteController',
    'action' => 'successPayment'
];

$routes['paypal/cancel-payment'] = [
    'name' => 'Track_paymentCancel',
    'controller' => 'SiteController',
    'action' => 'cancelPayment'
];

//$routes['tracks/purchase/{trackId}'] = [
//    'name' => 'Track_purchase',
//    'controller' => 'SiteController',
//    'action' => 'purchase'
//];

/**
 * Will display a specific track.
 * Information displayed should be based,
 * on the users status (logged_in & bought_track)
 */
$routes['tracks/{trackId}'] = [
    'name' => 'Track_retrieve',
    'controller' => 'SiteController',
    'action' => 'retrieveTrack'
];

/**
 * Sends the user to the specific course site,
 * where videos and other information are available.
 * Page requires a user, and that the track is purchased
 */
$routes['tracks/{trackId}/course/{courseId}'] = [
    'name' => 'Course_retrieve',
    'controller' => 'SiteController',
    'action' => 'retrieveCourse'
];