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
  public static function toUId($baseId, $multiplier = 1)
  {
      return base_convert($baseId * $multiplier, 10, 36);
  }

  public static function fromUId($uid, $multiplier = 1)
  {
      return (int) base_convert($uid, 36, 10) / $multiplier;
  }
  
  /* RETRIEVING AN ITEM FROM THE DATABASE
   *
   */
  public static function retrieveItem($id)
  {
  
    $db = DBConnection::db();
    $platforms = API::getPlatforms();
  
    $query  = "SELECT `type`, `name`, `artist`, `album`, `image`";
  
    while (list($pId, $pObject) = each($platforms))
    {
      if (!$pObject->isActiveForSearch()) continue; 
      $query .= ", `"._TABLE_LINK_PREFIX.$pObject->getSafeName()."`"; // column name = link_DEEZER, etc ...
    }
    reset($platforms);

    $query .= " FROM `items` WHERE id=:id LIMIT 1;";
  
    $statement = $db->prepare($query);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);

    // Executes the query
    $exe = $statement->execute();
  
    if (!$exe || $exe == false || $statement->rowCount() != 1 ) {
      // Looks like it's expired ?
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetch(PDO::FETCH_ASSOC);

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
  public static function retrieveCustomer($consumerKey)
  {
  
    $db = DBConnection::db();
    $statement = $db->prepare('SELECT `consumer_secret` FROM `api_clients` WHERE `active` = TRUE AND `consumer_key` = :consumer_key LIMIT 1');
    $statement->bindParam(':consumer_key', $consumerKey, PDO::PARAM_STR);
    $exe = $statement->execute();
  
    if (!$exe || $exe == false || $statement->rowCount() != 1 ) {
      // Looks like it's a no
      return false;
    } else {
      // Fetch the info
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      if (!$row) return false;
      
      return $row['consumer_secret'];
    }
    
  }
  
}
