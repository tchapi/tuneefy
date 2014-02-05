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
  public static function retrievePick($date)
  {

    $db = DBConnection::db();

    $query = "SELECT name, artist, album, image, link FROM `picks` WHERE date = :date LIMIT 1";
    $statement = $db->prepare($query);
    $statement->bindParam(':date', $date, PDO::PARAM_STR);

    // Executes the query
    $exe = $statement->execute();
    
    // Returns true if the query was well executed
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      if (!$row) return false;
      
      return array("name" => $row["name"], "album" => $row["album"], "artist" => $row["artist"], "image" => $row["image"], "link" => $row["link"]);
    }
  }
  
  
  // retrieveLastSharedTrack function
  // We look for the last shared track
  // Only tracks, not albums
  //
  public static function retrieveLastSharedTrack()
  {

    $db = DBConnection::db();

    $query  = "SELECT name, artist, album, image, id FROM `items` WHERE type="._TABLE_TRACK." ORDER BY id DESC LIMIT 1";
    $statement = $db->prepare($query);

    // Executes the query
    $exe = $statement->execute();
    
    // Returns true if the query was well executed
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      if (!$row) return false;
      
      return array("name" => $row["name"], "album" => $row["album"], "artist" => $row["artist"], "image" => $row["image"], "id" => $row["id"]);
    }
  }

  
  // retrieveLastSharedItem function
  // We look for the last shared item
  // tracks OR albums
  //
  public static function retrieveLastSharedItem()
  {

    $db = DBConnection::db();

    $query  = "SELECT type, name, artist, album, image, id FROM `items` ORDER BY id DESC LIMIT 1";
    $statement = $db->prepare($query);

    // Executes the query
    $exe = $statement->execute();
    
    // Returns true if the query was well executed
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      if (!$row) return false;
      
      return array("type" => $row["type"], "name" => $row["name"], "album" => $row["album"], "artist" => $row["artist"], "image" => $row["image"], "id" => $row["id"]);
    }
  }  
  
  // retrieveMostViewedTrack function
  // We look for the most viewed track in the past period span
  // Only tracks, not ALBUMS
  //
  public static function retrieveMostViewedTrack($time_start, $time_end)
  {

    $db = DBConnection::db();

    $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
    $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
    $queryMax  = "SELECT item_id, COUNT(item_id) AS nb FROM `stats_items`";
    $queryMax .= " WHERE stats_items.date >= :time_start AND stats_items.date <= :time_end GROUP BY `item_id` ORDER BY nb DESC LIMIT 1";
    
    $statement = $db->prepare($queryMax);
    $statement->bindParam(':time_start', $time_start, PDO::PARAM_STR);
    $statement->bindParam(':time_end', $time_end, PDO::PARAM_STR);

    // Executes the query
    $exe = $statement->execute();
    
    if (!$exe || $exe == false ) {
      return false;
    } else {
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      $idMax = $row['item_id'];
    }
    
    $query  = "SELECT id, name, artist, album, image FROM `items` WHERE id = :id_max AND type="._TABLE_TRACK." LIMIT 1"; 
    
    $statement = $db->prepare($query);
    $statement->bindParam(':id_max', $idMax, PDO::PARAM_INT);

    // Executes the query
    $exe = $statement->execute();
    
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      if (!$row) return false;
      
      return array("name" => $row["name"], "album" => $row["album"], "artist" => $row["artist"], "image" => $row["image"], "id" => $row["id"]);
    }

  }

  
  // retrieveMostViewedItem function
  // We look for the most viewed item in the past period span
  // tracks AND albums
  //
  public static function retrieveMostViewedItem($time_start, $time_end)
  {

    $db = DBConnection::db();

    $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
    $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
    $queryMax  = "SELECT item_id, COUNT(item_id) AS nb FROM `stats_items`";
    $queryMax .= " WHERE stats_items.date >= :time_start AND stats_items.date <= :time_end GROUP BY `item_id` ORDER BY nb DESC LIMIT 1";

    $statement = $db->prepare($queryMax);
    $statement->bindParam(':time_start', $time_start, PDO::PARAM_STR);
    $statement->bindParam(':time_end', $time_end, PDO::PARAM_STR);

    // Executes the query
    $exe = $statement->execute();
    
    if (!$exe || $exe == false ) {
      return false;
    } else {
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      $idMax = $row['item_id'];
    }
    
    $query  = "SELECT id, type, name, artist, album, image FROM `items` WHERE id = :id_max LIMIT 1";
    
    $statement = $db->prepare($query);
    $statement->bindParam(':id_max', $idMax, PDO::PARAM_INT);

    // Executes the query
    $exe = $statement->execute();
    
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetch(PDO::FETCH_ASSOC);
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
  public static function platformsHits($time_start = null, $time_end = null, $via = 'all')
  {
  
    $db = DBConnection::db();

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
      $query .= " date >= :time_start AND date <= :time_end GROUP BY platform ORDER BY nb DESC";

    } else {
      
      $query  = "SELECT platform, COUNT(id) AS nb FROM `stats_platforms`";
      $query .= $queryAppend;
      $query .= " GROUP BY platform ORDER BY nb DESC";
    }
    
    $statement = $db->prepare($query);
    $statement->bindParam(':time_start', $time_start, PDO::PARAM_STR);
    $statement->bindParam(':time_end', $time_end, PDO::PARAM_STR);

    // Executes the query
    $exe = $statement->execute();
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $result[$row["platform"]] = $row["nb"];
      }
      return $result;
    }
  
  }
  
  //  
  // totalPlatformsHits()
  // Gets the total number of clicks to all the platforms, from the search page or from the share page
  //
  public static function totalPlatformsHits($time_start = null, $time_end = null, $via = 'all')
  {
  
    $db = DBConnection::db();

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
      $query .=  " date >= :time_start AND date <= :time_end";

    } else {
      
      $query  = "SELECT COUNT(id) AS nb FROM `stats_platforms`";
      $query .= $queryAppend;

    }
        
    $statement = $db->prepare($query);
    $statement->bindParam(':time_start', $time_start, PDO::PARAM_STR);
    $statement->bindParam(':time_end', $time_end, PDO::PARAM_STR);

    // Executes the query
    $exe = $statement->execute();
  
    // Returns the total if the query was well executed
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      return intval($row["nb"]);
    }
  
  }

  //  
  // totalViews()
  // Gets the total number of times a track OR album was 'viewed' (the share page was displayed)
  //  
  public static function totalViews($time_start = null, $time_end = null)
  {
  
    $db = DBConnection::db();

    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query  = "SELECT count(id) AS nb FROM `stats_items`";
      $query .= " WHERE date >= :time_start AND date <= :time_end";

    } else {
      
      $query  = "SELECT count(id) AS nb FROM `stats_items`";

    }
    
    $statement = $db->prepare($query);
    $statement->bindParam(':time_start', $time_start, PDO::PARAM_STR);
    $statement->bindParam(':time_end', $time_end, PDO::PARAM_STR);

    // Executes the query
    $exe = $statement->execute();
    
    // Returns the total if the query was well executed
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      return intval($row["nb"]);
    }
  
  }

  //  
  // mostViewedArtists()
  // Gets the $limite most Viewed Artists (all clicks on a tuneefy link)
  // Album OR track
  //  
  public static function mostViewedArtists($time_start = null, $time_end = null, $limite = 5)
  {
  
    $db = DBConnection::db();
    $limite = intval($limite);

    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query  = "SELECT count(stats_items.id) AS nb, artist FROM `stats_items`";
      $query .= " INNER JOIN `items` ON stats_items.item_id = items.id";
      $query .= " WHERE stats_items.date >= :time_start AND stats_items.date <= :time_end GROUP BY `artist` ORDER BY nb DESC LIMIT :limite";

      $statement = $db->prepare($query);
      $statement->bindParam(':time_start', $time_start, PDO::PARAM_STR);
      $statement->bindParam(':time_end', $time_end, PDO::PARAM_STR);

    } else {
      
      $query  = "SELECT count(stats_items.id) AS nb, artist FROM `stats_items`";
      $query .= " INNER JOIN `items` ON stats_items.item_id = items.id";
      $query .= " GROUP BY `artist` ORDER BY nb DESC LIMIT :limite";

      $statement = $db->prepare($query);

    }
    
    $statement->bindParam(':limite', $limite, PDO::PARAM_INT);

    // Executes the query
    $exe = $statement->execute();
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
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
  public static function mostViewedTracks($time_start = null, $time_end = null, $limite = 5)
  {
  
    $db = DBConnection::db();

    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query  = "SELECT item_id, count(stats_items.id) AS nb, artist, name FROM `stats_items`";
      $query .= " INNER JOIN `items` ON stats_items.item_id = items.id";
      $query .= " WHERE stats_items.date >= :time_start AND stats_items.date <= :time_end AND items.type="._TABLE_TRACK." GROUP BY `item_id` ORDER BY nb DESC LIMIT :limite";
    
      $statement = $db->prepare($query);
      $statement->bindParam(':time_start', $time_start, PDO::PARAM_STR);
      $statement->bindParam(':time_end', $time_end, PDO::PARAM_STR);

    } else {
      
      $query  = "SELECT item_id, count(stats_items.id) AS nb, artist, name FROM `stats_items`";
      $query .= " INNER JOIN `items` ON stats_items.item_id = items.id";
      $query .= " WHERE items.type="._TABLE_TRACK." GROUP BY `item_id` ORDER BY nb DESC LIMIT :limite";

      $statement = $db->prepare($query);

    }
    
    
    $statement->bindParam(':limite', intval($limite), PDO::PARAM_INT);

    // Executes the query
    $exe = $statement->execute();
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      
      $result = null;
      $precedent = 0;
        
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      
        $key = $row["item_id"]."|".$row["artist"]."|".$row["name"];
      
        if (isset($result[$key]))
          $precedent = $result[$key];
          
        $result[$key] = $precedent + intval($row["nb"]); // += in the case where a track is duplicated in the database
      }
      
      arsort($result);
      
      return $result;
    }
  
  }

  //  
  // mostViewedAlbums()
  // Gets the $limite most viewed Albums (all clicks on a tuneefy link : /a/[id] )
  // Only albums (no tracks)
  // 
  public static function mostViewedAlbums($time_start = null, $time_end = null, $limite = 5)
  {
  
    $db = DBConnection::db();

    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query  = "SELECT item_id, count(stats_items.id) AS nb, artist, album FROM `stats_items`";
      $query .= " INNER JOIN `items` ON stats_items.item_id = items.id";
      $query .= " WHERE stats_items.date >= :time_start AND stats_items.date <= :time_end AND items.type="._TABLE_ALBUM." GROUP BY `item_id` ORDER BY nb DESC LIMIT :limite";

      $statement = $db->prepare($query);
      $statement->bindParam(':time_start', $time_start, PDO::PARAM_STR);
      $statement->bindParam(':time_end', $time_end, PDO::PARAM_STR);

    } else {
      
      $query  = "SELECT item_id, count(stats_items.id) AS nb, artist, album FROM `stats_items`";
      $query .= " INNER JOIN `items` ON stats_items.item_id = items.id";
      $query .= " WHERE items.type="._TABLE_ALBUM." GROUP BY `item_id` ORDER BY nb DESC LIMIT :limite";

      $statement = $db->prepare($query);

    }
    
    $statement->bindParam(':limite', intval($limite), PDO::PARAM_INT);

    // Executes the query
    $exe = $statement->execute();
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      
      $result = null;
      $precedent = 0;
        
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      
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
  public static function firstDateInDB()
  {
  
    $db = DBConnection::db();

    $queryUnion = "SELECT date FROM `items` UNION ALL SELECT date FROM `stats_items` UNION ALL SELECT date FROM `stats_platforms`";
    $query  = "SELECT MIN(tmp.date) AS minDate FROM ($queryUnion) tmp";
  
    $statement = $db->prepare($query);

    // Executes the query
    $exe = $statement->execute();
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row =  $statement->fetch(PDO::FETCH_ASSOC);
      return $row["minDate"];
    }

  }
  
  //  
  // platformsCatalogueSpan()
  // Retrieves, for each platform, the number of tracks that have a link on this platform, thus giving an idea of the catalogue span for the common end-user searches
  //
  public static function platformsCatalogueSpan($time_start = null, $time_end = null)
  {
  
    $db = DBConnection::db();

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
      
      $query .= " WHERE date >= :time_start AND date <= :time_end AND type="._TABLE_TRACK;

    } else {

      $query .= " WHERE type="._TABLE_TRACK;

    }
    
    $statement = $db->prepare($query);
    $statement->bindParam(':time_start', $time_start, PDO::PARAM_STR);
    $statement->bindParam(':time_end', $time_end, PDO::PARAM_STR);

    // Executes the query
    $exe = $statement->execute();
    
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetchAll(PDO::FETCH_ASSOC);

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
  public static function lastSearches($time_start = null, $time_end = null, $limite = 5)
  {
  
    $db = DBConnection::db();

    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query = "SELECT query FROM `stats_search_query` ";
      $query .= " WHERE date >= :time_end AND date <= :time_end ORDER BY `date` DESC LIMIT :limite";
      
      $statement = $db->prepare($query);
      $statement->bindParam(':time_start', $time_start, PDO::PARAM_STR);
      $statement->bindParam(':time_end', $time_end, PDO::PARAM_STR);

    } else {
      
      $query = "SELECT query FROM `stats_search_query` ORDER BY `date` DESC LIMIT :limite";

      $statement = $db->prepare($query);

    }

    $statement->bindParam(':limite', $limite, PDO::PARAM_INT);

    // Executes the query
    $exe = $statement->execute();
      
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $result[] = $row["query"];
      }
      return $result;
    }
    
  }
  
  //  
  // popularSearches()
  // Gets the popular search queries from the database
  //
  public static function popularSearches($time_start = null, $time_end = null, $limite = 5)
  {
  
    $db = DBConnection::db();

    if ($time_start != null && $time_end != null) {
    
      $time_start = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_start));
      $time_end = strftime("%Y-%m-%d %H:%M:%S", strtotime($time_end));
      
      $query = "SELECT query, COUNT(query) AS nb FROM `stats_search_query` ";
      $query .= " WHERE date >= :time_start AND date <= :time_end GROUP BY `query` ORDER BY `nb` DESC LIMIT :limite";
      
      $statement = $db->prepare($query);
      $statement->bindParam(':time_start', $time_start, PDO::PARAM_STR);
      $statement->bindParam(':time_end', $time_end, PDO::PARAM_STR);

    } else {
      
      $query = "SELECT query, COUNT(query) AS nb FROM `stats_search_query` GROUP BY `query` ORDER BY `nb` DESC LIMIT :limite";

      $statement = $db->prepare($query);

    }
    
    $statement->bindParam(':limite', $limite, PDO::PARAM_INT);

    // Executes the query
    $exe = $statement->execute();
      
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $result[$row["query"]] = $row["nb"];
      }
      return $result;
    }
    
  }
  
  //  
  // totalEmails()
  // Gets the number of emails registered in the coming_soon table
  //
  public static function totalEmails()
  {
  
    $db = DBConnection::db();

    $query = "SELECT COUNT(id) AS nb FROM `coming_soon_mails`;";
    $statement = $db->prepare($query);

    // Executes the query
    $exe = $statement->execute();
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      return $row["nb"];
    }

  }
  
  //  
  // getEmails()
  // Gets all the emails in the database
  //
  public static function getEmails()
  {
  
    $db = DBConnection::db();

    // Latest first
    $query = "SELECT id, mail FROM `coming_soon_mails` ORDER BY date DESC";
    $statement = $db->prepare($query);

    // Executes the query
    $exe = $statement->execute();
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $result[$row["id"]] = $row["mail"];
      }
      return $result;
    }
  }
  
  //  
  // getTracks()
  // Gets all the tracks in the database
  //
  public static function getTracks($offset = 0, $limite = 20)
  {
  
    $db = DBConnection::db();

    // Latest first
    $query = "SELECT id, name, album, artist, image FROM `items` WHERE type="._TABLE_TRACK." ORDER BY date DESC LIMIT :offset,:limite";
    $statement = $db->prepare($query);
    $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
    $statement->bindParam(':limite', $limite, PDO::PARAM_INT);

    // Executes the query
    $exe = $statement->execute();
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $result[$row["id"]] = array('name' => $row["name"], 'artist' => $row["artist"],'album' => $row["album"],'image' => $row["image"]);
      }
      return $result;
    }
  }
  
  //  
  // getNbOfTracks()
  // Gets the number of tracks in the database
  //
  public static function getNbOfTracks()
  {
  
    $db = DBConnection::db();

    // Latest first
    $query = "SELECT COUNT(id) AS nb FROM `items` WHERE type="._TABLE_TRACK;
    $statement = $db->prepare($query);

    // Executes the query
    $exe = $statement->execute();
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      return $row["nb"];
    }
  }
  
  //  
  // getAlbums()
  // Gets all the albums in the database
  //
  public static function getAlbums($offset = 0, $limite = 20)
  {
  
    $db = DBConnection::db();

    // Latest first
    $query = "SELECT id, album, artist, image FROM `items` WHERE type="._TABLE_ALBUM." ORDER BY date DESC LIMIT :offset,:limite";
    $statement = $db->prepare($query);
    $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
    $statement->bindParam(':limite', $limite, PDO::PARAM_INT);

    // Executes the query
    $exe = $statement->execute();
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $result[$row["id"]] = array('artist' => $row["artist"],'album' => $row["album"],'image' => $row["image"]);
      }
      return $result;
    }
  }

  
  //  
  // getNbOfAlbums()
  // Gets the number of album in the database
  //
  public static function getNbOfAlbums()
  {
  
    $db = DBConnection::db();

    // Latest first
    $query = "SELECT COUNT(id) AS nb FROM `items` WHERE type="._TABLE_ALBUM;
    $statement = $db->prepare($query);

    // Executes the query
    $exe = $statement->execute();
  
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      return $row["nb"];
    }
  }
    
  //  
  // addAPick()
  // Adds a pick from an id in the tracks table
  //
  public static function addAPick($id)
  {
    
    $db = DBConnection::db();

    // Latest first
    $query = "SELECT id, name, album, artist, image FROM `items` WHERE id=:id AND type="._TABLE_TRACK." LIMIT 1";
    $statement = $db->prepare($query);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);

    // Executes the query
    $exe = $statement->execute();
    
    if (!$exe || $exe == false ) {
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetchAll(PDO::FETCH_ASSOC);

      $queryDate = "SELECT MAX(date) AS maxDate FROM `picks`";
      $statementDate = $db->prepare($queryDate);

      // Executes the query
      $exeDate = $statementDate->execute();
      
      if (!$exeDate || $exeDate == false ) {
        return false;
      } else {
        $rowDate = $statementDate->fetch(PDO::FETCH_ASSOC);
        $maxDate = strftime("%Y-%m-%d", strtotime($rowDate['maxDate']." + 1 day"));
      }
      
      $queryPick = "INSERT INTO `picks` (name, artist, album, image, link, date) VALUES(:name, :artist, :album, :image, :link, :max_date)";

      $statementPick = $db->prepare($queryPick);
      $statementPick->bindParam(':name', $row["name"], PDO::PARAM_STR);
      $statementPick->bindParam(':artist', $row["artist"], PDO::PARAM_STR);
      $statementPick->bindParam(':album', $row["album"], PDO::PARAM_STR);
      $statementPick->bindParam(':image', $row["image"], PDO::PARAM_STR);
      $statementPick->bindParam(':link', _SITE_URL.'/t/'.DBUtils::toUid($row["id"],_BASE_MULTIPLIER), PDO::PARAM_STR);
      $statementPick->bindParam(':max_date', $maxDate, PDO::PARAM_STR);
    
      // Executes the query
      $exePick = $statementPick->execute();
      
      if (!$exePick || $exePick == false ) {
        return false;
      } else {
        return true;
      }
    }
  }

}
