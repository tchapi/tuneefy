<?php

/* *************************************************** */
/*                                                     */
/*                  STATISTICS HELPER                  */
/*                                                     */
/* *************************************************** */

class DBStats {
  
  // retrievePick function
  // We look for the pick on that day if any
  //
  public static function retrievePick($date){

    $query = "SELECT name, artist, album, image, link FROM `picks` WHERE";
    $query .= sprintf(" date = \"%s\" LIMIT 1", 
                        mysql_real_escape_string($date)
                        );
                      
    // Executes the query
    $exe = mysql_query($query);
    
    // Returns true if the query was well executed
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      if (!$row) return false;
      
      return array("name" => $row["name"], "album" => $row["album"], "artist" => $row["artist"], "image" => $row["image"], "link" => $row["link"]);
    }
  }
  
  
  // retrieveLastSharedTrack function
  // We look for the last shared track
  // Only tracks, not albums
  //
  public static function retrieveLastSharedTrack(){

    $query  = "SELECT name, artist, album, image, id FROM `items` WHERE type="._TABLE_TRACK." ORDER BY id DESC LIMIT 1";
                      
    // Executes the query
    $exe = mysql_query($query);
    
    // Returns true if the query was well executed
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      if (!$row) return false;
      
      return array("name" => $row["name"], "album" => $row["album"], "artist" => $row["artist"], "image" => $row["image"], "id" => $row["id"]);
    }
  }

  
  // retrieveLastSharedItem function
  // We look for the last shared item
  // tracks OR albums
  //
  public static function retrieveLastSharedItem(){

    $query  = "SELECT type, name, artist, album, image, id FROM `items` ORDER BY id DESC LIMIT 1";
                      
    // Executes the query
    $exe = mysql_query($query);
    
    // Returns true if the query was well executed
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      if (!$row) return false;
      
      return array("type" => $row["type"], "name" => $row["name"], "album" => $row["album"], "artist" => $row["artist"], "image" => $row["image"], "id" => $row["id"]);
    }
  }  
  
  // retrieveMostViewedTrack function
  // We look for the most viewed track in the past period span
  // Only tracks, not ALBUMS
  //
  public static function retrieveMostViewedTrack($time_start, $time_end){

    $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
    $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
    $queryMax  = "SELECT item_id, COUNT(item_id) AS nb FROM `stats_items`";
    $queryMax .= sprintf(" WHERE stats_items.date >= \"%s\" AND stats_items.date <= \"%s\" GROUP BY `item_id` ORDER BY nb DESC LIMIT 1", 
                        mysql_real_escape_string($time_start),
                        mysql_real_escape_string($time_end)
                        );

    // Executes the query
    $exe = mysql_query($queryMax);
    
    if (!$exe || $exe == false ) {
      return false;
    } else {
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      $idMax = $row['item_id'];
    }
    
    $query  = sprintf("SELECT id, name, artist, album, image FROM `items` WHERE id = \"%d\" AND type="._TABLE_TRACK." LIMIT 1", 
                        mysql_real_escape_string(intval($idMax))
                        );
    
    // Executes the query
    $exe = mysql_query($query);
    
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      if (!$row) return false;
      
      return array("name" => $row["name"], "album" => $row["album"], "artist" => $row["artist"], "image" => $row["image"], "id" => $row["id"]);
    }

  }

  
  // retrieveMostViewedItem function
  // We look for the most viewed item in the past period span
  // tracks AND albums
  //
  public static function retrieveMostViewedItem($time_start, $time_end){

    $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
    $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
    $queryMax  = "SELECT item_id, COUNT(item_id) AS nb FROM `stats_items`";
    $queryMax .= sprintf(" WHERE stats_items.date >= \"%s\" AND stats_items.date <= \"%s\" GROUP BY `item_id` ORDER BY nb DESC LIMIT 1", 
                        mysql_real_escape_string($time_start),
                        mysql_real_escape_string($time_end)
                        );

    // Executes the query
    $exe = mysql_query($queryMax);
    
    if (!$exe || $exe == false ) {
      return false;
    } else {
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      $idMax = $row['item_id'];
    }
    
    $query  = sprintf("SELECT id, type, name, artist, album, image FROM `items` WHERE id = \"%d\" LIMIT 1", 
                        mysql_real_escape_string(intval($idMax))
                        );
    
    // Executes the query
    $exe = mysql_query($query);
    
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      if (!$row) return false;
      
      return array("type" => $row["type"], "name" => $row["name"], "album" => $row["album"], "artist" => $row["artist"], "image" => $row["image"], "id" => $row["id"]);
    }

  }
  
  /* ********************************************************************** */
  /*  STAT RETRIEVAL
   *  Utility functions to get the stats from the database
   */
  /* ********************************************************************** */
  
  //  
  // platformsHits()
  // Gets the number of clicks to a given platform, from the search page or from the share page
  //
  public static function platformsHits($time_start = null, $time_end = null, $via = 'all') {
  
    // Decide if we apply a condition to where the click comes from
    if ($via == 'share')
      $queryAppend = " WHERE `item_id` IS NOT NULL";
    else if ($via == 'search')
      $queryAppend = " WHERE `item_id` IS NULL";
    else if ($via == 'all')
      $queryAppend = "";
  
    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query  = "SELECT platform, COUNT(id) AS nb FROM `stats_platforms`";
      $query .= $queryAppend;
      if ($queryAppend == "") $query .= " WHERE"; else $query .= " AND";
      $query .= sprintf(" date >= \"%s\" AND date <= \"%s\" GROUP BY platform ORDER BY nb DESC", 
                        mysql_real_escape_string($time_start),
                        mysql_real_escape_string($time_end)
                        );
    } else {
      
      $query  = "SELECT platform, COUNT(id) AS nb FROM `stats_platforms`";
      $query .= $queryAppend;
      $query .= " GROUP BY platform ORDER BY nb DESC";
    }
    
    // Executes the query
    $exe = mysql_query($query);
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = mysql_fetch_array($exe, MYSQL_ASSOC)) {
        $result[$row["platform"]] = $row["nb"];
      }
      return $result;
    }
  
  }
  
  //  
  // totalPlatformsHits()
  // Gets the total number of clicks to all the platforms, from the search page or from the share page
  //
  public static function totalPlatformsHits($time_start = null, $time_end = null, $via = 'all') {
  
    // Decide if we apply a condition to where the click comes from
    if ($via == 'share')
      $queryAppend = " WHERE `item_id` IS NOT NULL";
    else if ($via == 'search')
      $queryAppend = " WHERE `item_id` IS NULL";
    else if ($via == 'all')
      $queryAppend = "";
  
    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query  = "SELECT COUNT(id) AS nb FROM `stats_platforms`";
      $query .= $queryAppend;
      if ($queryAppend == "") $query .= " WHERE"; else $query .= " AND";
      $query .= sprintf(" date >= \"%s\" AND date <= \"%s\"", 
                        mysql_real_escape_string($time_start),
                        mysql_real_escape_string($time_end)
                        );
    } else {
      
      $query  = "SELECT COUNT(id) AS nb FROM `stats_platforms`";
      $query .= $queryAppend;

    }
    
    // Executes the query
    $exe = mysql_query($query);
  
    // Returns the total if the query was well executed
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      return intval($row["nb"]);
    }
  
  }

  //  
  // totalViews()
  // Gets the total number of times a track OR album was 'viewed' (the share page was displayed)
  //  
  public static function totalViews($time_start = null, $time_end = null) {
  
    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query  = "SELECT count(id) AS nb FROM `stats_items`";
      $query .= sprintf(" WHERE date >= \"%s\" AND date <= \"%s\"", 
                        mysql_real_escape_string($time_start),
                        mysql_real_escape_string($time_end)
                        );
    } else {
      
      $query  = "SELECT count(id) AS nb FROM `stats_items`";

    }
    
    // Executes the query
    $exe = mysql_query($query);
    
    // Returns the total if the query was well executed
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      return intval($row["nb"]);
    }
  
  }

  //  
  // mostViewedArtists()
  // Gets the $limite most Viewed Artists (all clicks on a tuneefy link)
  // Album OR track
  //  
  public static function mostViewedArtists($time_start = null, $time_end = null, $limite = 5) {
  
    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query  = "SELECT count(stats_items.id) AS nb, artist FROM `stats_items`";
      $query .= " INNER JOIN `items` ON stats_items.item_id = items.id";
      $query .= sprintf(" WHERE stats_items.date >= \"%s\" AND stats_items.date <= \"%s\" GROUP BY `artist` ORDER BY nb DESC LIMIT %d", 
                        mysql_real_escape_string($time_start),
                        mysql_real_escape_string($time_end),
                        mysql_real_escape_string(intval($limite))
                        );
    } else {
      
      $query  = "SELECT count(stats_items.id) AS nb, artist FROM `stats_items`";
      $query .= " INNER JOIN `items` ON stats_items.item_id = items.id";
      $query .= sprintf(" GROUP BY `artist` ORDER BY nb DESC LIMIT %d", 
                        mysql_real_escape_string(intval($limite))
                        );
    }
    
    // Executes the query
    $exe = mysql_query($query);
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = mysql_fetch_array($exe, MYSQL_ASSOC)) {
        $result[$row["artist"]] = $row["nb"];
      }
      return $result;
    }
  
  }
  
  //  
  // mostViewedTracks()
  // Gets the $limite most viewed Tracks (all clicks on a tuneefy link : /t/[id] )
  // Only tracks (no ALBUMS)
  // 
  public static function mostViewedTracks($time_start = null, $time_end = null, $limite = 5) {
  
    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query  = "SELECT item_id, count(stats_items.id) AS nb, artist, name FROM `stats_items`";
      $query .= " INNER JOIN `items` ON stats_items.item_id = items.id";
      $query .= sprintf(" WHERE stats_items.date >= \"%s\" AND stats_items.date <= \"%s\" AND items.type="._TABLE_TRACK." GROUP BY `item_id` ORDER BY nb DESC LIMIT %d", 
                        mysql_real_escape_string($time_start),
                        mysql_real_escape_string($time_end),
                        mysql_real_escape_string(intval($limite))
                        );
    } else {
      
      $query  = "SELECT item_id, count(stats_items.id) AS nb, artist, name FROM `stats_items`";
      $query .= " INNER JOIN `items` ON stats_items.item_id = items.id";
      $query .= sprintf(" WHERE items.type="._TABLE_TRACK." GROUP BY `item_id` ORDER BY nb DESC LIMIT %d", 
                        mysql_real_escape_string(intval($limite))
                        );
    }
    
    // Executes the query
    $exe = mysql_query($query);
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      
      $result = null;
      $precedent = 0;
        
      while ($row = mysql_fetch_array($exe, MYSQL_ASSOC)) {
      
        $key = $row["item_id"]."|".$row["artist"]."|".$row["name"];
      
        if (isset($result[$key]))
          $precedent = $result[$key];
          
        $result[$key] = $precedent + intval($row["nb"]); // += in the case where a track is duplicated in the database
      }
      
      arsort($result );
      
      return $result;
    }
  
  }

  //  
  // mostViewedAlbums()
  // Gets the $limite most viewed Albums (all clicks on a tuneefy link : /a/[id] )
  // Only albums (no tracks)
  // 
  public static function mostViewedAlbums($time_start = null, $time_end = null, $limite = 5) {
  
  if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query  = "SELECT item_id, count(stats_items.id) AS nb, artist, album FROM `stats_items`";
      $query .= " INNER JOIN `items` ON stats_items.item_id = items.id";
      $query .= sprintf(" WHERE stats_items.date >= \"%s\" AND stats_items.date <= \"%s\" AND items.type="._TABLE_ALBUM." GROUP BY `item_id` ORDER BY nb DESC LIMIT %d", 
                        mysql_real_escape_string($time_start),
                        mysql_real_escape_string($time_end),
                        mysql_real_escape_string(intval($limite))
                        );
    } else {
      
      $query  = "SELECT item_id, count(stats_items.id) AS nb, artist, album FROM `stats_items`";
      $query .= " INNER JOIN `items` ON stats_items.item_id = items.id";
      $query .= sprintf(" WHERE items.type="._TABLE_ALBUM." GROUP BY `item_id` ORDER BY nb DESC LIMIT %d", 
                        mysql_real_escape_string(intval($limite))
                        );
    }
    
    // Executes the query
    $exe = mysql_query($query);
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      
      $result = null;
      $precedent = 0;
        
      while ($row = mysql_fetch_array($exe, MYSQL_ASSOC)) {
      
        $key = $row["item_id"]."|".$row["artist"]."|".$row["album"];
      
        if (isset($result[$key]))
          $precedent = $result[$key];
          
        $result[$key] = $precedent + intval($row["nb"]); // += in the case where a track is duplicated in the database
      }
      
      arsort($result );
      
      return $result;
    }

  
  }
  
  /* ********************************************************************** */
  /*  STAT RETRIEVAL (ADMIN SIDE)
   *  Utility functions to get the stats from the database
   */
  /* ********************************************************************** */
  
  //  
  // firstDateInDB()
  // Gets the earliest date from the database (the date the first track was shared)
  //
  public static function firstDateInDB(){
  
    $queryUnion = "SELECT date FROM `items` UNION ALL SELECT date FROM `stats_items` UNION ALL SELECT date FROM `stats_platforms`";
    $query  = "SELECT MIN(tmp.date) AS minDate FROM ($queryUnion) tmp";
  
    // Executes the query
    $exe = mysql_query($query);
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      return $row["minDate"];
    }
  }
  
  //  
  // platformsCatalogueSpan()
  // Retrieves, for each platform, the number of tracks that have a link on this platform, thus giving an idea of the catalogue span for the common end-user searches
  //
  public static function platformsCatalogueSpan($time_start = null, $time_end = null){
  
    $platforms = API::getPlatforms();
    
    $query  = "SELECT COUNT(id) as total";
    
    while (list($pId, $pObject) = each($platforms))
    {
      $query .= ", COUNT( NULLIF( `"._TABLE_LINK_PREFIX.$pObject->getSafeName()."`, 'null' ) ) AS "._TABLE_LINK_PREFIX.$pObject->getSafeName();
    }
    reset($platforms);

    $query .= " FROM `items`";
      
    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query .= sprintf(" WHERE date >= \"%s\" AND date <= \"%s\" AND type="._TABLE_TRACK, 
                        mysql_real_escape_string($time_start),
                        mysql_real_escape_string($time_end));
    } else {
      $query .= " WHERE type="._TABLE_TRACK;
    }
    
    // Executes the query
    $exe = mysql_query($query);
    
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);

      $result['total'] = intval($row["total"]);

      while (list($pId, $pObject) = each($platforms))
      {
        $linkProperty = _TABLE_LINK_PREFIX.$pObject->getSafeName();
        $result[$pObject->getId()] = intval($row[$linkProperty]);
      }
      
      return $result;
    }
    
  }
  
  //  
  // lastSearches()
  // Gets the latest search queries from the database
  //
  public static function lastSearches($time_start = null, $time_end = null, $limite = 5){
  
    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query = "SELECT query FROM `stats_search_query` ";
      $query .= sprintf(" WHERE date >= \"%s\" AND date <= \"%s\" ORDER BY `date` DESC LIMIT %d", 
                        mysql_real_escape_string($time_start),
                        mysql_real_escape_string($time_end),
                        mysql_real_escape_string(intval($limite))
                        );
    } else {
      
      $query = sprintf("SELECT query FROM `stats_search_query` ORDER BY `date` DESC LIMIT %d",
                        mysql_real_escape_string(intval($limite))
                        );
    }

    // Executes the query
    $exe = mysql_query($query);
      
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = mysql_fetch_array($exe, MYSQL_ASSOC)) {
        $result[] = $row["query"];
      }
      return $result;
    }
    
  }
  
  //  
  // popularSearches()
  // Gets the popular search queries from the database
  //
  public static function popularSearches($time_start = null, $time_end = null, $limite = 5){
  
    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query = "SELECT query, COUNT(query) AS nb FROM `stats_search_query` ";
      $query .= sprintf(" WHERE date >= \"%s\" AND date <= \"%s\" GROUP BY `query` ORDER BY `nb` DESC LIMIT %d", 
                        mysql_real_escape_string($time_start),
                        mysql_real_escape_string($time_end),
                        mysql_real_escape_string(intval($limite))
                        );
    } else {
      
      $query = sprintf("SELECT query, COUNT(query) AS nb FROM `stats_search_query` GROUP BY `query` ORDER BY `nb` DESC LIMIT %d",
                        mysql_real_escape_string(intval($limite))
                        );
    }
    
    // Executes the query
    $exe = mysql_query($query);
      
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = mysql_fetch_array($exe, MYSQL_ASSOC)) {
        $result[$row["query"]] = $row["nb"];
      }
      return $result;
    }
    
  }
  
  //  
  // totalEmails()
  // Gets the number of emails registered in the coming_soon table
  //
  public static function totalEmails(){
  
    $query = "SELECT COUNT(id) AS nb FROM `coming_soon_mails`;";
  
    // Executes the query
    $exe = mysql_query($query);
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      return $row["nb"];
    }
  }
  
  //  
  // getEmails()
  // Gets all the emails in the database
  //
  public static function getEmails(){
  
    // Latest first
    $query = "SELECT id, mail FROM `coming_soon_mails` ORDER BY date DESC";
  
    // Executes the query
    $exe = mysql_query($query);
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = mysql_fetch_array($exe, MYSQL_ASSOC)) {
        $result[$row["id"]] = $row["mail"];
      }
      return $result;
    }
  }
  
  //  
  // getTracks()
  // Gets all the tracks in the database
  //
  public static function getTracks($offset = 0, $limite = 20){
  
    // Latest first
    $query = sprintf("SELECT id, name, album, artist, image FROM `items` WHERE type="._TABLE_TRACK." ORDER BY date DESC LIMIT %d,%d",
                     mysql_real_escape_string(intval($offset)),
                     mysql_real_escape_string(intval($limite))
                        );
  
    // Executes the query
    $exe = mysql_query($query);
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = mysql_fetch_array($exe, MYSQL_ASSOC)) {
        $result[$row["id"]] = array('name' => $row["name"], 'artist' => $row["artist"],'album' => $row["album"],'image' => $row["image"]);
      }
      return $result;
    }
  }
  
  //  
  // getNbOfTracks()
  // Gets the number of tracks in the database
  //
  public static function getNbOfTracks(){
  
    // Latest first
    $query = "SELECT COUNT(id) AS nb FROM `items` WHERE type="._TABLE_TRACK;
      
    // Executes the query
    $exe = mysql_query($query);
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      return $row["nb"];
    }
  }
  
  //  
  // getAlbums()
  // Gets all the albums in the database
  //
  public static function getAlbums($offset = 0, $limite = 20){
  
    // Latest first
    $query = sprintf("SELECT id, album, artist, image FROM `items` WHERE type="._TABLE_ALBUM." ORDER BY date DESC LIMIT %d,%d",
                     mysql_real_escape_string(intval($offset)),
                     mysql_real_escape_string(intval($limite))
                        );
  
    // Executes the query
    $exe = mysql_query($query);
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = mysql_fetch_array($exe, MYSQL_ASSOC)) {
        $result[$row["id"]] = array('artist' => $row["artist"],'album' => $row["album"],'image' => $row["image"]);
      }
      return $result;
    }
  }

  
  //  
  // getNbOfAlbums()
  // Gets the number of album in the database
  //
  public static function getNbOfAlbums(){
  
    // Latest first
    $query = "SELECT COUNT(id) AS nb FROM `items` WHERE type="._TABLE_ALBUM;
      
    // Executes the query
    $exe = mysql_query($query);
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      return $row["nb"];
    }
  }
    
  //  
  // addAPick()
  // Adds a pick from an id in the tracks table
  //
  public static function addAPick($id){
  
    // Latest first
    $query = sprintf("SELECT id, name, album, artist, image FROM `items` WHERE id='%d' AND type="._TABLE_TRACK." LIMIT 1",
                     mysql_real_escape_string(intval($id))
                        );
  
    // Executes the query
    $exe = mysql_query($query);
    
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);

      $queryDate = "SELECT MAX(date) AS maxDate FROM `picks`";
  
      $exeDate = mysql_query($queryDate);
      
      if (!$exeDate || $exeDate == false ) {
        return false;
      } else {
        $rowDate = mysql_fetch_array($exeDate, MYSQL_ASSOC);
        $maxDate = strftime("%Y-%m-%d", strtotime($rowDate['maxDate']." + 1 day"));
      }
      
      $queryPick = sprintf("INSERT INTO `picks` (name, artist, album, image, link, date) VALUES('%s', '%s', '%s', '%s', '%s', '%s')",
                      mysql_real_escape_string($row["name"]),
                      mysql_real_escape_string($row["artist"]),
                      mysql_real_escape_string($row["album"]),
                      mysql_real_escape_string($row["image"]),
                      mysql_real_escape_string(_SITE_URL.'/t/'.DBUtils::toUid($row["id"],_BASE_MULTIPLIER)),
                      mysql_real_escape_string($maxDate)
                        );
    
      // Executes the query
      $exe = mysql_query($queryPick);
      
      if (!$exe || $exe == false ) {
        return false;
      } else {
        return true;
      }
    }
  }

}
