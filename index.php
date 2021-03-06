<?php

require('config.php');

require(_PATH.'include/request.php');
require(_PATH.'include/langs/i18nHelper.php');
  
// On to the header and content

if ($action == 'search') {
    require(_PATH.'include/processTodaysPick.php');
    require(_PATH.'templates/header.php');
    require(_PATH.'templates/renderPreSearch.php');
    require(_PATH.'templates/renderSearch.php');
} elseif ($action == 'widget') {
    require(_PATH.'widget/widget.php');
} elseif ($action == 'track' || $action == 'album') {
    require(_PATH.'include/processShare.php');
    require(_PATH.'templates/header.php');
    require(_PATH.'templates/renderShare.php');
} elseif ($action == 'trends') {
    require(_PATH.'include/processStats.php');
    require(_PATH.'templates/header.php');
    require(_PATH.'templates/renderStats.php');
} elseif ($action == 'about') {
    require(_PATH.'templates/header.php');
    require(_PATH.'templates/renderAbout.php');
} elseif ($action == 'playlists') {
    require(_PATH.'templates/header.php');
    require(_PATH.'templates/renderPlaylists.php');
} elseif ($action == '404') {
    require(_PATH.'templates/header_light.php');
    require(_PATH.'templates/404.php');
} elseif ($action == '503') {
    require(_PATH.'templates/header_light.php');
    require(_PATH.'templates/503.php');
} elseif ($action == 'woops') {
    require(_PATH.'templates/header_light.php');
    require(_PATH.'templates/woops.php');
} elseif ($action == "old_ie") {
    require(_PATH.'templates/header_light.php');
    require(_PATH.'templates/old_ie.php');
} else {
    require(_PATH.'templates/header_light.php');
    require(_PATH.'templates/404.php');
}

// On to the footer
if ($action == '404' ||
    $action == '503' ||
    $action == 'woops' ||
    $action == 'old_ie' ) {
    require(_PATH.'templates/footer_light.php');
} else {
    if ($action != 'widget') {
        require(_PATH.'templates/footer.php');
    }
}
