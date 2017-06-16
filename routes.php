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