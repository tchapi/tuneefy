<?php

  require('../config.php');
  
  if (isset($_GET['adv'])) {
    $advanced = ($_GET['adv'] == 1);
  } else $advanced = false;
  
  if (isset($_GET['file'])) {
    $file = ($_GET['file'] == 1); // If we output to stdout or if we propose the download of a file
  } else $file = false;
  
  if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
  } else $mode = 'compiled_code';
  
  $now = time();
  $build_version = " (build ".$now.")";

  $closureUrl = "http://closure-compiler.appspot.com/compile";
  
  $baseUrl = _PATH."js/dev/";
  $baseHttpUrl = _SITE_URL."/js/dev/";
  
  /* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */
  /* GET FILES AND PREG REPLACE SOME CONSTANTS */
  /* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */
  $fullCode  = file_get_contents($baseUrl.'ControlFreak.class.js', FILE_USE_INCLUDE_PATH);
  $fullCode .= file_get_contents($baseUrl.'Model/Tuneefy.links.class.js', FILE_USE_INCLUDE_PATH);
  $fullCode .= file_get_contents($baseUrl.'UI/SliderUI.class.js', FILE_USE_INCLUDE_PATH);
  
    $patterns = array( '/wrapCheckboxWithDivs/', '/disableTextSelection/', '/handleRadius/', '/currentlyClicking/');
    $replacements = array('wCw', 'dTs', 'hRa','cuC');

  $fullCode .= preg_replace($patterns, $replacements, file_get_contents($baseUrl.'UI/iphone-style.js', FILE_USE_INCLUDE_PATH));
  
    $patterns = array('/strictMode/', '/currentItem/', '/flatten/');
    $replacements = array('sM', 'cT', 'ftN');
  
  $fullCode .= preg_replace($patterns, $replacements, file_get_contents($baseUrl.'Model/Tuneefy.item.class.js', FILE_USE_INCLUDE_PATH));;
  
    $patterns = array('/initialController/','/controller/','/currentRawItem/','/this.platform/', '/currentItem/', '/getPlaylistData/', '/findPlaylistEquivalents/');
    $replacements = array('icl','cl','crt','this.p', 'ct', 'gPdt', 'fPe');

  $fullCode .= preg_replace($patterns, $replacements, file_get_contents($baseUrl.'Model/Tuneefy.class.js', FILE_USE_INCLUDE_PATH));
  
    $patterns = array('/initialController/','/controller/','/alertsDiv/','/platform/', '/currentItem/');
    $replacements = array('icl','cl','aD','p', 'ct');
    
  $fullCode .= preg_replace($patterns, $replacements, file_get_contents($baseUrl.'UI/AlertUI.class.js', FILE_USE_INCLUDE_PATH));
  
  //$fullCode .= file_get_contents($baseHttpUrl.'Messages.js.php', FILE_USE_INCLUDE_PATH, $context);

    $patterns = array('/initialController/','/controller/','/queryField/','/strictModeCheckBox/', '/selectedPlatforms/', '/searchButton/','/this.platforms/','/cookieValue/','/cookieContent/','/tempSelectedPlatforms/', '/optionsButton/');
    $replacements = array('icl','cl','qF','sMcB', 'sP', 'sB', 'this.ps', 'cV', 'cC', 'tSP', 'opB');
 
  $fullCode .= preg_replace($patterns, $replacements, file_get_contents($baseUrl.'UI/SearchUI.class.js', FILE_USE_INCLUDE_PATH));
  
    $patterns = array('/initialController/','/controller/','/maxResultsPerPage/','/maxPages/', '/resultsDiv/', '/waitingDiv/','/initialModel/','/model/', '/makeDiv/', '/makeLink/', '/makeListenLink/', '/makeImg/', '/findFeat/', '/cleanForDisplay/', '/displayPlaylist/');
    $replacements = array('icl','cl','mrPP','mP', 'rD', 'wD', 'iM', 'mO', 'mD', 'mL', 'mLL', 'mIm', 'fF', 'cFD', 'dspP');
 
  $fullCode .= preg_replace($patterns, $replacements, file_get_contents($baseUrl.'UI/ResultRenderUI.class.js', FILE_USE_INCLUDE_PATH));
 
    $patterns = array('/platformButtons/', '/\.queryField/', '/\.loginFields/', '/searchButton/', '/\.loginForm/', '/resetField/', '/controller/');
    $replacements = array('pBtns', '.qUf', '.lGf', 'sBtn', '.lFrm', 'rzF', 'cl');
 
  
 
  $fullCode .= preg_replace($patterns, $replacements, file_get_contents($baseUrl.'UI/PlaylistsUI.class.js', FILE_USE_INCLUDE_PATH));
  /* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */
  
  /* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */
  /* REPLACE COMMON STUFF */
  /* ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** */
    $patterns = array('/initUIInitialState/','/bindObjects/', '/tuneefy.query.updated/','/tuneefy.alert.invalidQuery/','/tuneefy.alert.platformReturnedNoResult/','/tuneefy.alert.pReturnedNoResult/', '/tuneefy.alert.apiSeemsDown/','/tuneefy.search.newResultsReady/','/tuneefy.alert.noResultsFound/','/tuneefy.search.finished/','/tuneefy.search.launched/', '/findOne/', '/getSpan/', '/addLink/', '/merge/', '/parseDataFromPlatform/', '/tuneefy.retrieve.playlist/', '/tuneefy.retrieve.finished/', '/tuneefy.process.finished/', '/tuneefy.search.popstate/', '/tuneefy.login.succeeded/', '/tuneefy.login.failed/', '/tuneefy.login.playlist/');
    $replacements = array('iis','bOb', 't1', 't2', 't3', 't3', 't4', 't5', 't6', 't7', 't8', 'fO', 'gSp', 'aLnk', 'mRG', 'pDfP', 't9', 't10', 't11', 't12', 't13', 't14', 't15');
 
  $fullCode = preg_replace($patterns, $replacements, $fullCode);
  

  
  // Stripping console.log
  $fullCode = preg_replace('/(console.log\(.*\);)/i', '', $fullCode);

  if ($advanced) {
    $postData = http_build_query(
      array(
          'output_info' => $mode,
          'warning_level' => 'verbose',
          'output_format' => 'text',
          'compilation_level' => 'ADVANCED_OPTIMIZATIONS',
          'js_code' => "window['ResultRenderUI'] = ResultRenderUI;window['AlertUI'] = AlertUI;window['SearchUI'] = SearchUI;window['ControlFreak'] = ControlFreak;window['Tuneefy'] = Tuneefy;window['Links']=Links;window['Track']=Track;".$fullCode,
          'externs_url' => 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js',
      )
    );
  } else {
    $postData = http_build_query(
      array(
          'output_info' => $mode,
          'warning_level' => 'verbose',
          'output_format' => 'text',
          'compilation_level' => 'SIMPLE_OPTIMIZATIONS',
          'js_code' => $fullCode
      )
    );  
  
  }
  
  $opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded;charset=utf-8',
        'content' => $postData
    )
  );
  $context  = stream_context_create($opts);
  $result = file_get_contents($closureUrl, false, $context);
  
  if ($mode == 'compiled_code')
    $result = "/*! Tuneefy v4.0".$build_version." release 2014 | http://tuneefy.com/ (c) 2011-2014, tchap */".$result;
    
  $result = trim($result);
  
  if ($file) {
  
    header("Pragma: public"); // required
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false); // required for certain browsers 
    header("Content-Type: application/force-download");
    // change, added quotes to allow spaces in filenames, by Rajkumar Singh
    header("Content-Disposition: attachment; filename=\"tuneefy.min.js\";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".strlen($result));
    ob_start();
    print $result;
    exit();
    
  } else {
  
    echo $result;
    
  }
  
  /*
  
  // Trying to write file
  $filename = _PATH."js/min/tuneefy.min.js";

  if (!$handle = fopen($filename, 'w')) {
       //echo "Impossible d'ouvrir le fichier ($filename)";
       exit;
  }

  if (fwrite($handle, $result) === FALSE) {
      //echo "Impossible d'écrire dans le fichier ($filename)";
      exit;
  }

  //echo "L'écriture dans le fichier ($filename) a réussi";

  fclose($handle);
  
  */