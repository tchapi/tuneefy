<?php

require(_PATH.'include/database/DBUtils.class.php');
require(_PATH.'include/database/DBLogger.class.php');
require(_PATH.'include/database/DBConnection.class.php');

// We retrieve the base_id and check it is correct :
$base_id = DBUtils::fromUId($request, _BASE_MULTIPLIER);

if ($base_id != intval($base_id)) {
    // Looks like the ID is not good
    header('Location: '._SITE_URL.'/404');
    exit;
}

// Retrieves the item
$item = DBUtils::retrieveItem($base_id);
if (!$item) {
    header('Location: '._SITE_URL.'/404');
}

// We add a hit
DBLogger::addHit($base_id);

// Checks if we want to display an album or a track
if ($action == 'track') {
    $requestedType = _TABLE_TRACK;
    $complementaryShortCode = '/a/';
} elseif ($action == 'album') {
    $requestedType = _TABLE_ALBUM;
    $complementaryShortCode = '/t/';
}

// Is the requested type correct ?
if (intval($item['type']) != intval($requestedType)) {
    // Wrong type but item exists : we redirect to have the correct link
    header('Location: '._SITE_URL.$complementaryShortCode.$request);
    exit;
}

// Track OR album information
$name   = trim(stripslashes($item['name']));
$artist = trim(stripslashes($item['artist']));
$album  = trim(stripslashes($item['album']));
$image  = trim(stripslashes($item['image']));
