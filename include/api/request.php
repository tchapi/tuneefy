<?php

  // We get the request
  if(!isset($_SERVER['SERVER_NAME']) || strpos(_API_URL,$_SERVER['SERVER_NAME']) === false ){
    header ("Location: http://tuneefy.com/404");
    exit;
  }

  if (isset($_REQUEST['m']) && $_REQUEST['m'] != "") {
  
    $action = 'api';
    $calledMethod = trim($_REQUEST['m']);
    
  } else {
  
    // We need to go home
    $action = 'api_doc';
    $calledMethod = null;
    
  }
  