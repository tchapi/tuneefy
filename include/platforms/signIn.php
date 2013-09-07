<?php

  require('../../config.php');
  
  require(_PATH.'include/api/RestUtils.class.php');

// Somehow secure AJAX Request
// For those that don't set the HTTP REFERER, it works (au cas ou...) 
if (strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH']) != "xmlhttprequest" || 
    ( isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], _SITE_URL) === false) ) {
  header("Location: /503");
  exit;
}

if (isset($_GET['id']) && isset($_GET['username']) && isset($_GET['password'])){
 
  
  // Actual search
  try {

    $retour = API::getPlatform($_GET['id'])->signIn($_GET['username'], $_GET['password']); 
    if ($retour == null) $retour = array( 'error' => true);

  } catch (PlatformTimeoutException $e) {

    $retour = array( 'error' => true);

  } catch (Exception $eGeneral) {

    $retour = array( 'error' => true);

  }

  RestUtils::sendResponse(200, $retour, "json", false, null); // false = not api mode, null = no key for json
  
} else {

  RestUtils::sendResponse(404, null, "json", false, null); // false = not api mode, null = no key for json

}  
