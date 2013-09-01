<?php

  require('../../config.php');
  
  require(_PATH.'include/api/RestUtils.class.php');

// Somehow secure AJAX Request
// For those that don't set the HTTP REFERER, it works (au cas ou...) 
if (strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH']) != "xmlhttprequest" || 
    ( isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], _SITE_URL) === false ) ) {
  header("Location: /503");
  exit;
}
  
if (isset($_GET['id']) && isset($_GET['query'])){
      
  // Type of search : track or album
  if (isset($_GET['itemType'])){
    if ($_GET['itemType'] == 0) $itemType = 'track';
    else if ($_GET['itemType'] == 1) $itemType = 'album';
    else $itemType = 'track'; // Default to track search
  } else {
    $itemType = 'track'; // Default to track search
  }
  
  if (!isset($_GET['limit']))
    $_GET['limit'] = 999;
    
  $retour = API::search($_GET['query'], intval($_GET['id']), $itemType, $_GET['limit']);
  
  // $retour = 0 : no result
  // $retour = null : platform Timeout
  if ($retour === null ) {
    $status = 204;
  } else {
    $status = 200;
  }

  if (isset($_GET['json_key']))
    $json_key = $_GET['json_key'];
  else
    $json_key = null;

  RestUtils::sendResponse($status, $retour, "json", false, $json_key); // false = not api mode
  
} else {

  RestUtils::sendResponse(404, null, "json", false, $json_key); // false = not api mode

}  
