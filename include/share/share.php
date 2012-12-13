<?php

  require('../../config.php');

  $parentCalledFromPost = true;
  require(_PATH.'include/database/DBUtils.class.php');
  require(_PATH.'include/database/DBConnection.class.php');

  // What type of share is it ?
  if (isset($_REQUEST['itemType'])) {
    if ($_REQUEST['itemType'] == _TABLE_TRACK) {
      $itemType = _TABLE_TRACK;
      $shortCode = '/t/';
    } else if ($_REQUEST['itemType'] == _TABLE_ALBUM) {
      $itemType = _TABLE_ALBUM;
      $shortCode = '/a/';
    } else {
      header('Location: '._SITE_URL);
      die(0);
    }
  } else {
    header('Location: '._SITE_URL);
    die(0);
  }

  // We check we have some info
  if ( ((!isset($_REQUEST['name']) || $_REQUEST['name'] == "") && $itemType == 'track') || 
       ((!isset($_REQUEST['album']) || $_REQUEST['album'] == "") && $itemType == 'album') ||
         !isset($_REQUEST['artist']) || $_REQUEST['artist'] == "") {
    header('Location: '._SITE_URL);
    die(0);
  }
  
  $name = trim(html_entity_decode($_REQUEST['name'], ENT_COMPAT, "UTF-8"));
  $artist = trim(html_entity_decode($_REQUEST['artist'], ENT_COMPAT, "UTF-8"));
  $album = trim(html_entity_decode($_REQUEST['album'], ENT_COMPAT, "UTF-8"));
  $image = trim($_REQUEST['image']);

  // We arrange the future $query values that we are going to insert into our final lookup and insert queries
  $insertQueryColumns = "";
  $insertQueryValues = "";
  $lookupQuery = "";
  
  $platforms = API::getPlatforms();
  
  while (list($pId, $pObject) = each($platforms))
  {
    $postName = _TABLE_LINK_PREFIX.$pObject->getId();
    $lookupName = _TABLE_LINK_PREFIX.$pObject->getSafeName();
    
    // If there is no POST parameter for this platform
    if (!$pObject->isActiveForSearch() || !isset($_REQUEST[$postName])) continue;
    
    $insertQueryColumns .= ", ".$lookupName;
    
    $postValue = trim($_REQUEST[$postName]);

    $lookupQuery .= sprintf(" AND `".$lookupName."`='%s'",
                            mysql_real_escape_string($postValue)
                            );
    $insertQueryValues  .= sprintf(", '%s'",mysql_real_escape_string($postValue));
    
  }
  reset($platforms);

  // Looks for the track if already shared (name + artist + album only)
  $query  = "SELECT `id`";
  $query .= " FROM `items`";
  $query .= sprintf(" WHERE `name`='%s' AND `artist`='%s' AND `album`='%s' AND `type`=".$itemType, 
                    mysql_real_escape_string($name),
                    mysql_real_escape_string($artist),
                    mysql_real_escape_string($album)
                    );
  $query .= $lookupQuery;
  $query .= " LIMIT 1;";
  
  // Executes the query
  $exe = mysql_query($query);

  if ($exe && mysql_num_rows($exe) == 1 ) {
  
    $found = true;
    $row = mysql_fetch_row($exe);

    $shortUrl = _SITE_URL.$shortCode.DBUtils::toUId($row[0], _BASE_MULTIPLIER);

    if (isset($_REQUEST['redirect']) && $_REQUEST['redirect'] == 1){
      header('Location: '.$shortUrl);
    } else {
      echo $shortUrl;
    }

    return;
  }
  
  $query  = "INSERT INTO `items` (type, name, artist, album, image".$insertQueryColumns.", date) ";
  
  $query .= sprintf("VALUES(".$itemType.", '%s', '%s', '%s', '%s'",
            mysql_real_escape_string($name),
            mysql_real_escape_string($artist),
            mysql_real_escape_string($album),
            mysql_real_escape_string($image));
  
  $query .= $insertQueryValues.", NOW())";

  // Executes the query
  $exe = mysql_query($query); 
  $id = mysql_insert_id();
  
  // If we fail ...
  
  if (!$exe || $exe == false || !$id || $id == 0) {
  
  	echo _SITE_URL."/woops";
  	  	
  } else {
  
    $shortUrl = _SITE_URL.$shortCode.DBUtils::toUId($id, _BASE_MULTIPLIER);

    if (isset($_REQUEST['redirect']) && $_REQUEST['redirect'] == 1){
      header('Location: '.$shortUrl);
    } else {
      echo $shortUrl;
    }

  }
  