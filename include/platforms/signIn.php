<?php

  require('../../config.php');
  
  require(_PATH.'include/api/RestUtils.class.php');

// Somehow secure AJAX Request
// For those that don't set the HTTP REFERER, it works (au cas ou...) 
if (strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH']) != "xmlhttprequest" || 
    ( isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "tuneefy.com") === false) ) {
  header("Location: /503");
  exit;
}

if (isset($_GET['id']) && isset($_GET['username']) && isset($_GET['password'])){
 
  //$retour = API::getPlatform($_GET['id'])->signIn($_GET['username'], $_GET['password']); 
  $retour = "test";
  // $retour = false ou NULL : failed
  // else array : succeeded

  RestUtils::sendResponse(200, $retour, "json", false, null); // false = not api mode, null = no key for json
  
} else {

  RestUtils::sendResponse(404, null, "json", false, null); // false = not api mode, null = no key for json

}  
