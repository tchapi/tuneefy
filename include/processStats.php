<?php

require(_PATH.'include/database/DBUtils.class.php');
require(_PATH.'include/database/DBStats.class.php');
require(_PATH.'include/database/DBConnection.class.php');

// Total SHARES
$totalViews = DBStats::totalViews();

// Platforms HITS
$platformsHits = DBStats::platformsHits(null, null, 'all');
$totalPlatformsHits = DBStats::totalPlatformsHits(null, null, 'all');

 // Most Shared ARTISTS and TRACKS
$listDisplayLimit = 5;
$mostViewedArtists = DBStats::mostViewedArtists(null, null, $listDisplayLimit);
$topViewedArtist = DBStats::mostViewedArtists(null, null, 1);
$mostViewedTracks = DBStats::mostViewedTracks(null, null, $listDisplayLimit);
$mostViewedAlbums = DBStats::mostViewedAlbums(null, null, $listDisplayLimit);
