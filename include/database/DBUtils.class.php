<?php

/* *************************************************** */
/*                                                     */
/*                      UTILITIES                      */
/*                                                     */
/* *************************************************** */

class DBUtils {
  
  /*  UTILS
   *  Utility functions to translate the id into a "hash" or "guid" (BASE 36 = [0-9a-z])
   */

  public static function toUId($baseId, $multiplier = 1) {
      return base_convert($baseId * $multiplier, 10, 36);
  }

  public static function fromUId($uid, $multiplier = 1) {
      return (int) base_convert($uid, 36, 10) / $multiplier;
  }
  
  /* RETRIEVING AN ITEM FROM THE DATABASE
   *
   */
  
  public static function retrieveItem($id){
  
    $platforms = API::getPlatforms();
  
    $query  = "SELECT `type`, `name`, `artist`, `album`, `image`";
  
    while (list($pId, $pObject) = each($platforms))
    {
      if (!$pObject->isActiveForSearch()) continue; 
      $query .= ", `"._TABLE_LINK_PREFIX.$pObject->getSafeName()."`"; // column name = link_DEEZER, etc ...
    }
    reset($platforms);
    
    $query .= " FROM `items`";
    // We'll check if the type is correct later on
    $query .= sprintf(" WHERE id=%d LIMIT 1;", mysql_real_escape_string(intval($id)));
  
    // Executes the query
    $exe = mysql_query($query); 
  
    if (!$exe || $exe == false || mysql_num_rows($exe) != 1 ) {
      // Looks like it's expired ?
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      if (!$row) return false;
      
      while (list($pId, $pObject) = each($platforms))
      {
        if (!$pObject->isActiveForSearch()) continue; 
        $linkProperty = _TABLE_LINK_PREFIX.$pObject->getSafeName();
        $links[$pObject->getId()] = web(trim(stripslashes($row[$linkProperty])));
      }
      
      return array("name" => $row["name"], "album" => $row["album"], "artist" => $row["artist"], "image" => $row["image"], "type" => $row["type"],"links" => $links);
    }
  
  }
  
  /* RETRIEVING A CUSTOMER OF THE API
   *
   */
  
  public static function retrieveCustomer($consumerKey){
  
    $query = sprintf("SELECT `consumer_secret` FROM `api_clients` WHERE `active` = TRUE AND `consumer_key` = \"%s\" LIMIT 1",
                        mysql_real_escape_string($consumerKey)
                        );

    // Executes the query
    $exe = mysql_query($query); 
  
    if (!$exe || $exe == false || mysql_num_rows($exe) != 1 ) {
      // Looks like it's a no
      return false;
    } else {
      // Fetch the info
      $row = mysql_fetch_array($exe, MYSQL_ASSOC);
      if (!$row) return false;
      
      return $row['consumer_secret'];
    }
    
  }
  
}
