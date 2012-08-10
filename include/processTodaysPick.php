<?php

  // We don't know yet
  $weHaveAPick = false;
  
  //$actualYear = date("Y");
  $actualDate = date("Y-m-d");
  $tomorrow = date("Y-m-d",strtotime( "+1 day" ));
  $sevenBefore = date("Y-m-d",strtotime( "-7 days" ));
  
  $failSilently = true;
  require(_PATH.'include/database/DBUtils.class.php');
  require(_PATH.'include/database/DBStats.class.php');
  require(_PATH.'include/database/DBConnection.class.php');
  
  // We get it from the database
  $pickOfTheDay = DBStats::retrievePick($actualDate);
  $mostViewed = DBStats::retrieveMostViewedItem($sevenBefore, $tomorrow);
  $lastShared = DBStats::retrieveLastSharedItem();
  
  if (isset($pickOfTheDay) && $pickOfTheDay != false && $pickOfTheDay != NULL) {
    $weHaveAPick[0] = true;
    $tp_name[0] = $pickOfTheDay["name"];
    $tp_artist[0] = $pickOfTheDay["artist"];
    $tp_album[0] = $pickOfTheDay["album"];
    $tp_image[0] = $pickOfTheDay["image"];
    $tp_link[0] = $pickOfTheDay["link"];
  }
  if (isset($mostViewed) && $mostViewed != false && $mostViewed != NULL) {
    $weHaveAPick[1] = true;
    
    if ($mostViewed["type"] == _TABLE_TRACK) {
      $tp_name[1] = $mostViewed["name"];
      $tp_album[1] = $mostViewed["album"];
      $urlIdentifier = "/t/";
    } else {
      $tp_name[1] = $mostViewed["album"];
      $tp_album[1] = "";
      $urlIdentifier = "/a/";
    }
      
    $tp_artist[1] = $mostViewed["artist"];
    $tp_image[1] = $mostViewed["image"];
    
    $tp_link[1] = _SITE_URL.$urlIdentifier.DBUtils::toUid(intval($mostViewed["id"]), _BASE_MULTIPLIER);

  }
  if (isset($lastShared) && $lastShared != false && $lastShared != NULL) {
    $weHaveAPick[2] = true;
    
    if ($lastShared["type"] == _TABLE_TRACK) {
      $urlIdentifier = "/t/";
      $tp_name[2] = $lastShared["name"];
      $tp_album[2] = $lastShared["album"];
    } else {
      $urlIdentifier = "/a/";
      $tp_name[2] = $lastShared["album"];
      $tp_album[2] = "";
    }
      
    $tp_artist[2] = $lastShared["artist"];
    $tp_image[2] = $lastShared["image"];

    $tp_link[2] = _SITE_URL.$urlIdentifier.DBUtils::toUid(intval($lastShared["id"]), _BASE_MULTIPLIER);
  }