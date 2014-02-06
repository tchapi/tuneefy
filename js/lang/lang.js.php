<?php

require('../../config.php');
require(_PATH.
    'include/langs/i18nHelper.php');
header("Content-Type: application/javascript, charset=UTF-8");

?>

$DOMAIN = "<?php echo _COOKIE_DOMAIN; ?>";

/* Messages */
$search_alert = "<?php $i18n->search_alert; ?>";
$api_alert = "<?php $i18n->api_alert; ?>";
$no_result = "<?php $i18n->no_result; ?>";
$invalid_query = "<?php $i18n->invalid_query; ?>";

$query_label = "<?php $i18n->query_label; ?>";
$results_found = "<?php $i18n->results_found; ?>";
$result_found_widget = "<?php $i18n->result_found_widget; ?>";
$various_albums = "<?php $i18n->various_albums; ?>";
$listen_to = "<?php $i18n->listen_to; ?>";
$share = "<?php $i18n->share; ?>";
$share_tip = "<?php $i18n->share_tip; ?>";
$featuring = "<?php $i18n->featuring; ?>";
$album_cover = "<?php $i18n->album_cover; ?>";
$header_track = "<?php $i18n->header_track; ?>";
$header_artist = "<?php $i18n->header_artist; ?>";
$header_album = "<?php $i18n->header_album; ?>";
$header_available = "<?php $i18n->header_available; ?>";
$back_to_top = "<?php $i18n->back_to_top; ?>";
$yes = "<?php $i18n->yes; ?>";
$no = "<?php $i18n->no; ?>";
$tracks = "<?php $i18n->_tracks; ?>";
$albums = "<?php $i18n->_albums; ?>";

$playlists_query_label = "<?php $i18n->playlists_query_label; ?>";
$playlists_results_found = "<?php $i18n->playlists_results_found; ?>";