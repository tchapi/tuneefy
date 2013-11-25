<?php

/* *************************************************** */
/*                                                     */
/*                       LOGGERS                       */
/*                                                     */
/* *************************************************** */

class DBLogger {

  /*  ADD STATS
   *  Utility functions to add statistics to the database
   */ 
   
  // addHit function
  // A share page has been displayed
  // Adds a hit in the stats_tracks table
  //
  public static function addHit($track_id)
  {

    $db = DBConnection::db();

    if (!isset($_SERVER["HTTP_REFERER"]))
      $_SERVER["HTTP_REFERER"] = "";
      
    $query = "INSERT INTO `stats_items` (item_id, referer, date)";
    $query .= " VALUES(:track_id, :referer, NOW());";

    $statement = $db->prepare($query);
    $statement->bindParam(':track_id', $track_id, PDO::PARAM_INT);
    $statement->bindParam(':referer', $_SERVER["HTTP_REFERER"], PDO::PARAM_STR);

    // Executes the query
    $exe = $statement->execute();
    
    // Returns true if the query was well executed and returned a single line
    if ($exe && $statement->rowCount() == 1 ) {
      return true;
    } else return false;
    
  }
  
  // addGlobalHit function (careful)
  // A link to Deezer, Spotify, ... has been clicked
  // Adds a hit in the stats_platforms table
  //
  public static function addGlobalHit($platform, $track_id = 0)
  {
  
    $db = DBConnection::db();

    if ($track_id == 0) {
      // We come from the search page
      $query = "INSERT INTO `stats_platforms` (platform, date)";
      $query .= " VALUES(:platform, NOW());";

      $statement = $db->prepare($query);
      $statement->bindParam(':platform', $platform, PDO::PARAM_INT);

    } else {
      // We come from a share page
      $query = "INSERT INTO `stats_platforms` (platform, item_id, date)";
      $query .= " VALUES(:platform, :track_id, NOW());";

      $statement = $db->prepare($query);
      $statement->bindParam(':platform', $platform, PDO::PARAM_INT);
      $statement->bindParam(':track_id', $track_id, PDO::PARAM_INT);

    }

    // Executes the query
    $exe = $statement->execute();
    
    // Returns true if the query was well executed and returned a single line
    if ($exe && $statement->rowCount() == 1 ) {
      return true;
    } else return false;
  
  }
  
  // logSearchQuery function
  // A search request has been made, we log it
  // Adds a line in the stats_search_query
  //
  public static function logSearchQuery($searchQuery, $from = "site")
  {

    $db = DBConnection::db();

    if (!isset($_SERVER["HTTP_REFERER"]))
      $_SERVER["HTTP_REFERER"] = "";

    $query = "INSERT INTO `stats_search_query` (query, referer, origin, date)";
    $query .= " VALUES(:search_query, :referer, :from, NOW());";
            
    $statement = $db->prepare($query);
    $statement->bindParam(':search_query', trim($searchQuery), PDO::PARAM_STR);
    $statement->bindParam(':referer', $_SERVER["HTTP_REFERER"], PDO::PARAM_STR);
    $statement->bindParam(':from', $from, PDO::PARAM_STR);

    // Executes the query
    $exe = $statement->execute();
    
    // Returns true if the query was well executed and returned a single line
    if ($exe && $statement->rowCount() == 1 ) {
      return true;
    } else return false;
    
  }
  
}
