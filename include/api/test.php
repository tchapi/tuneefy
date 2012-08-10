<?php

  require('../../config.php');
  
  require(_PATH.'include/database/DBUtils.class.php');
  require(_PATH.'include/database/DBLogger.class.php');
  require(_PATH.'include/database/DBConnection.class.php');
  
  require(_PATH.'include/langs/i18nHelper.php');
  require(_PATH.'include/api/request.php');

  require(_PATH.'include/api/RestUtils.class.php');
  require(_PATH.'include/api/XMLHelper.class.php');
  
// Tuneefy platform
class Tuneefy extends Platform{

  public function __construct($default, $activeSearch, $activeAlbumSearch, $activeLookup) {

    $this->name = "Tuneefy";
    $this->safe_name = "_TUNEEFY";
    $this->type = 0;
    $this->color = "FFFFFF";

    $this->api_key = "a94a8fe5ccb19ba61c4c0873d391e987982fbbd3";
    $this->needsOAuth = true;
    $this->api_secret = "a94a8fe5ccb19ba61c4c0873d391e987982fbbd3";
    
    $this->api_endpoint = _API_URL.'/';
    $this->api_method = "GET";
    
    $this->query_endpoint = "search";
    $this->query_term = "q";
    $this->query_options = array("alt" => 'json', 'type' => 'track', 'platform' => 0);
    
    $this->query_album_endpoint = $this->query_endpoint;
    $this->query_album_term = $this->query_term; 
    $this->query_album_options = array("alt" => 'json');
    
    $this->lookup_endpoint = "lookup";
    $this->lookup_term = "q";
    $this->lookup_options = array("alt" => 'json', 'type' => 'album', 'platform' => 0);

    $this->track_permalink = null;
    
    // Search and lookup Behavior
    $this->isDefault = $default;
    $this->isActiveForSearch = $activeSearch;
    $this->isActiveForAlbumSearch = $activeAlbumSearch;
    $this->isActiveForLookup = $activeLookup;
    
  }
  
  public function hasPermalink($permalink) { return false; }

  public function getNormalizedResults($itemType, $query, $limit) {
    $result = $this->callPlatform($itemType, $query);
    return $result;
  }
  
  public function lookupPermalink($permalink) { return null; }  
  
  public function test($m){
    return $this->callPlatform($m, 'http://www.deezer.com/listen-10240179');
  }
  
}

  // Makes an API Call
  $test = new Tuneefy(true, true, true, true);
 
  var_dump($test->test($_GET['method']));
  
