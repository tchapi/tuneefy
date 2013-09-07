<?php

  require('../../config.php');
  
  $failSilently = true;
  require(_PATH.'include/database/DBUtils.class.php');
  require(_PATH.'include/database/DBLogger.class.php');
  require(_PATH.'include/database/DBConnection.class.php');
  
  require(_PATH.'include/api/RestUtils.class.php');

// Somehow secure AJAX Request
// For those that don't set the HTTP REFERER, it works (au cas ou...) 
if (strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH']) != "xmlhttprequest" || 
    ( isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], _SITE_URL) === false) ) {
  header("Location: /503");
  exit;
}

if (isset($_GET['str'])){
 
  $retour = API::lookup($_GET['str'], "playlist");

  if ($retour['lookedUpPlatform'] != -1 ) {

    try {

      $retour = API::getPlatform($retour['lookedUpPlatform'])->retrievePlaylist($_GET['str']);

    } catch (PlatformTimeoutException $e){

      $retour = null;

    }
  
  } else {
    
    $retour = null;
    
  }    
  
  // $retour = 0 : no result
  // $retour = null : platform Timeout
  if ($retour === null )
    $status = 204;
  else
    $status = 200;

  RestUtils::sendResponse($status, $retour, "json", false, null); // false = not api mode, null = no key for json
  
} else {

  RestUtils::sendResponse(404, null, "json", false, null); // false = not api mode, null = no key for json

}  
