<?php
  
  // To avoid notices and warning from PHP
  if (!isset($_SERVER['HTTP_USER_AGENT']))
    $_SERVER['HTTP_USER_AGENT'] = "N/A";

  // Check whether we are on IE < 8
  function ieversion() {
    $match=preg_match('/MSIE ([0-9]\.[0-9])/',$_SERVER['HTTP_USER_AGENT'],$reg);
    if($match==0)
      return 100;
    else
      return floatval($reg[1]);
  }
  
  // Check whether we're in an iframe or not
  function iframeMode() {
    return isset($_GET['embed']);
  }
  
  $iframeMode = iframeMode();

  // We set a FLAG to get the right $action and bypass the other loops
  $continue = true;

  // Check whether we are on IE < 8
  if (ieversion() < 8) {
    $action = "old_ie";
    $continue = false; 
  }

  // Mobile Detection
  $allMobilesDevices = "(android.*mobile|blackberry|iphone|ipod|avantgo|blazer|elaine|hiptop|palm|plucker|xiino|windows ce|iemobile|smartphone|windows phone os|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap|opera mini)";
  $mobile = false;
  if (preg_match("/" . $allMobilesDevices . "/i", $_SERVER['HTTP_USER_AGENT'])) $mobile = true;

  // 1. ERROR cases
  if (isset($_GET['e'])) {
    $action = $_GET['e']; // Either 404 (page not found / link expired) or 503 (Service unavailable)
    $continue = false;
  }
  if (isset($_GET['woops']) && $_GET['woops'] == "indeed") {
    $action = 'woops'; // Ahaha
    $continue = false;
  }
  
  // 2. SINGLE PAGES cases
  if (isset($_GET['r']) && $continue != false) {
    if ($_GET['r'] == 'trends') {
      if (!_DESACTIVATE_TRENDS && $mobile === false) $action = 'trends';
      else $action ='search';
      $continue = false;
    } else if ($_GET['r'] == 'facts') {
      $action = 'facts';
      $continue = false;
    } else if ($_GET['r'] == 'about') {
      $action = 'about';
      $continue = false;
    } else if ($_GET['r'] == 'playlists') {
      if (!_DESACTIVATE_PLAYLISTS && $mobile === false) $action = 'playlists';
      else $action ='search';
      $continue = false;
    }
  }  
  
  // Anything else

  // If we call the page with ?q=[something] or ?t=[code], we catch and clean the query for search or share
  if (isset($_GET['q']) && isset($_GET['widget']) && $continue != false) {
    $request = trim(stripslashes(urldecode($_GET['q'])));
    $action = 'widget';
  } else if (isset($_GET['q']) && $continue != false) {
    $request = trim(stripslashes(urldecode($_GET['q'])));
    $action = 'search';
  } else if (isset($_GET['t']) && $continue != false) {
    $request = trim(stripslashes($_GET['t']));
    $action = 'track';
  } else if (isset($_GET['a']) && $continue != false) {
    $request = trim(stripslashes($_GET['a']));
    $action = 'album';
  } else if( $continue != false) {
    $action = 'search';
  }
  
  // Developpement
  if (isset($_GET['dev']) && _DESACTIVATE_DEV == false) {
    $dev = true;
  } else $dev = false;
