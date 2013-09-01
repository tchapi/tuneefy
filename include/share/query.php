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
    ( isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], _SITE_URL) === false ) ) {
  header("Location: /503");
  exit;
}

if (isset($_GET['str'])){

  // We set the headers before sending the JSON content
  $retour = API::lookup($_GET['str'], "site");
  
} else {

  // We return a null JSON object - we don't cache it
  $retour = null;
 
}

  RestUtils::sendResponse(200, $retour, "json", false, null); // false = not api mode, null = no key for json
