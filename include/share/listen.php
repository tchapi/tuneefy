<?php

require('../../config.php');

$failSilently = true;
require(_PATH.'include/database/DBUtils.class.php');
require(_PATH.'include/database/DBLogger.class.php');
require(_PATH.'include/database/DBConnection.class.php');
  
if (isset($_GET['t']) && isset($_GET['p'])) {
    if (isset($_GET['i'])) {
        $track = intval(DBUtils::fromUId($_GET['i'], _BASE_MULTIPLIER));
    } else {
        $track = 0;
    }

    // Adds an entry
    DBLogger::addGlobalHit($_GET['p'], $track);
    
    header('Location: '.$_GET['t']);
    return false;
} else {
    header('Location: '._SITE_URL.'/woops');
}
