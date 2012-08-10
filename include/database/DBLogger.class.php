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
  public static function addHit($track_id){

    if (!isset($_SERVER["HTTP_REFERER"]))
      $_SERVER["HTTP_REFERER"] = "";
      
    $query = "INSERT INTO `stats_items` (item_id, referer, date)";
    $query .= sprintf(" VALUES('%d', '%s', NOW());", 
                      mysql_real_escape_string(intval($track_id)),
                      mysql_real_escape_string($_SERVER["HTTP_REFERER"])
                      );
                      
    // Executes the query
    $exe = mysql_query($query);
    
    // Returns true if the query was well executed and returned a single line
    if ($exe && mysql_affected_rows() == 1 ) {
      return true;
    } else return false;
    
  }
  
  // addGlobalHit function (careful)
  // A link to Deezer, Spotify, ... has been clicked
  // Adds a hit in the stats_platforms table
  //
  public static function addGlobalHit($platform, $track_id = 0){
  
    if ($track_id == 0) {
      // We come from the search page
      $query = "INSERT INTO `stats_platforms` (platform, date)";
      $query .= sprintf(" VALUES('%d', NOW());", 
                      mysql_real_escape_string(intval($platform))
                      );
    } else {
      // We come from a share page
      $query = "INSERT INTO `stats_platforms` (platform, item_id, date)";
      $query .= sprintf(" VALUES('%d', '%d', NOW());", 
                      mysql_real_escape_string(intval($platform)),
                      mysql_real_escape_string(intval($track_id))
                      );
    }
    
    // Executes the query
    $exe = mysql_query($query);
    
    // Returns true if the query was well executed and returned a single line
    if ($exe && mysql_affected_rows() == 1 ) {
      return true;
    } else return false;
  
  }
  
  // logSearchQuery function
  // A search request has been made, we log it
  // Adds a line in the stats_search_query
  //
  public static function logSearchQuery($searchQuery, $from = "site"){

    if (!isset($_SERVER["HTTP_REFERER"]))
      $_SERVER["HTTP_REFERER"] = "";

    $query = "INSERT INTO `stats_search_query` (query, referer, origin, date)";
    $query .= sprintf(" VALUES('%s', '%s', '%s', NOW());", 
                      mysql_real_escape_string(trim($searchQuery)),
                      mysql_real_escape_string($_SERVER["HTTP_REFERER"]),
                      mysql_real_escape_string($from)
                      );
                      
    // Executes the query
    $exe = mysql_query($query);
    
    // Returns true if the query was well executed and returned a single line
    if ($exe && mysql_affected_rows() == 1 ) {
      return true;
    } else return false;
    
  }
  
}
