<?php

/* *************************************************** */
/*                                                     */
/*                       API CLASS                     */
/*                                                     */
/* *************************************************** */

class API {
  
  // So as to store platforms, obviously
  private static $platforms = null;
  private static $nbPlatforms = 0;
  
  public static function setPlatforms($platformsUsed){
  
    API::$platforms = $platformsUsed;
    API::$nbPlatforms = count($platformsUsed);
    
  }
  
  public static function getPlatforms(){
  
    reset(API::$platforms);
    return API::$platforms;
    
  }
    
  public static function addPlatform($platform, $order){
  
    API::$platforms[$order] = $platform;
    API::$nbPlatforms += 1;

    ksort(API::$platforms);
    
  }
  
  public static function getPlatform($id){
  
    reset(API::$platforms);
    while (list($pId, $pObject) = each(API::$platforms))
    {
      if ($pObject->getId() == $id)
        return $pObject;
    }

    return false;

  }
  
  public static function getNbOfPlatforms(){
  
    return API::$nbPlatforms;
    
  }
  
  protected static function validateCall($calledMethod, $requestData){
  
    if (!isset($requestData->getData()->oauth_signature_method) ||
        $requestData->getData()->oauth_signature_method != "HMAC-SHA1" ||
        !isset($requestData->getData()->oauth_signature) ||
        !isset($requestData->getData()->oauth_consumer_key)) {
    
      return false;    
    }
   
    $url = _API_URL.'/'.$calledMethod;
    $signature = urldecode($requestData->getData()->oauth_signature);
    $consumer_key = urldecode($requestData->getData()->oauth_consumer_key);
    $consumer_secret = DBUtils::retrieveCustomer($consumer_key);
    
    if (!$consumer_secret)
      return false;
      
    // Makes a copy
    $parameters = $requestData->getData();
    $cleanParameters = array();
    
    // We need to strip down oauth_signature and create an array, not an object
    while (list($key, $value) = each($parameters)){
    
      if ($key != 'oauth_signature')
        $cleanParameters[$key] = urldecode($value);
    }
    
    $valid = OAuth::checkRequest($signature, $consumer_key, $consumer_secret, null, $requestData->getMethod(), $url, $cleanParameters);

    return $valid;
    
  }
  
  public static function processAPICall($calledMethod, $data) {
    
    $valid = true; //API::validateCall($calledMethod, $data);
    
    if (!$valid) {
    
      $returnedData = null;
      $statusCode = 401;
      
    } else {
    
      switch($calledMethod) {
      
        case 'lookup':
          if (isset($data->getData()->q)) {
            $returnedData = API::lookup($data->getData()->q, "api");
            $statusCode = 200;
          } else {
            // bad request, lacking query
            $returnedData = null;
            $statusCode = 400;
          }
          break;
          
        case 'search':
          if (isset($data->getData()->q) && isset($data->getData()->platform) && isset($data->getData()->type)) {
          
            if (isset($data->getData()->limit))
              $limit = $data->getData()->limit;
            else
              $limit = 999;
              
            $returnedData = API::search($data->getData()->q, $data->getData()->platform, $data->getData()->type, $limit);
            
            if ($returnedData == null) {
              $returnedData = null;
              $statusCode = 204;
            } else if ($returnedData == -42) {
              $returnedData = null;
              $statusCode = 406;
            } else { 
              $statusCode = 200;
            }
            
          } else {
            // bad request, lacking query
            $returnedData = null;
            $statusCode = 400;
          }
          break; 
        
        case 'aggregate':
          if (isset($data->getData()->q) && isset($data->getData()->type)) {
            
            if (isset($data->getData()->limit))
              $limit = $data->getData()->limit;
            else
              $limit = 999;
            
            $returnedData = API::aggregate($data->getData()->q, $data->getData()->type, $limit);
            $statusCode = 200;
          } else {
            // bad request, lacking query
            $returnedData = null;
            $statusCode = 400;
          }
          break; 
        
        default:
          // Method name is not good
          $returnedData = null;
          $statusCode = 501;
          break;
      }
    
    }
    
    RestUtils::sendResponse($statusCode, $returnedData, $data->getHttpAccept(), true, null); // true = api mode, null = no key for json   
  }
  
  /*
   *
   * LOOKUP FUNCTION
   *
   */
   
  public static function lookup($query, $from = "site") {
   
    // So far, we have no item
    $lookedUpItem = null;
    
    // By default we assume this is a simple query
    $lookedUpPlatform = -1;
    
    // Return value (default) 
    $retour = null;
    
    // For now we have not transformed
    $queryArray = array( 
      'initial' => $query, 
      'transformed' => $query
    );

    // Check that the request is well-formed
    if ($query){
      
      $query = trim(strip_tags($query));
      
      // We remove the trailing slash for our regex to work
      if (substr($query, -1) == '/') $query = substr($query, 0, -1);
      
      // We log the search query
      DBLogger::logSearchQuery($query, $from);

      // We look for the permalink
      $platforms = API::getPlatforms();

      while (list($pId, $pObject) = each($platforms))
      {

        if ($pObject->isActiveForLookup() || $from == "playlist") {
        // Is the platform active for lookup ?
          if($pObject->hasPermalink($query)) {
          // The permalink is correct for this platform

            if ($from == "playlist") {
              $lookedUpPlatform = $pObject->getId();
              break;
            }

            try {
              
              $result = $pObject->lookupPermalink($query);
              
            } catch (PlatformTimeoutException $e){ $result = null; }
                      
            if ($result) {
              // We have a non-null result
              $queryArray['transformed'] = str_replace(" ","+",trim($result['query']));

              $lookedUpItem = $result['track'];
              $lookedUpPlatform = $pObject->getId();
            }
            
            // We break anyway because we won't find anything else
            // since this was a correct permalink
            break;
            
          }
          
        }
      }
      
      // We create the PHP array containing the good values
      $retour = array('lookedUpPlatform' => $lookedUpPlatform, 'query' => $queryArray, 'lookedUpItem' => $lookedUpItem);
    
    } 
    
    return $retour;
  
  }
  
   /*
   *
   * SEARCH FUNCTION
   *
   */
   
  public static function search($query, $platformId, $itemType, $limit = 999) {
   
    $platform = API::getPlatform($platformId);
    
    if ( $platform == false || 
         (!$platform->isActiveForSearch() && $itemType == 'track') || 
         (!$platform->isActiveForAlbumSearch() && $itemType == 'album')) {
      return -42; // Unauthorized modification of the cookie ?
    }
    
    // Limits
    $limit = min(999,max(0,intval($limit)));
    
    // query decoding
    $query = trim(urldecode($query));
    
    // Actual search
    try {
    
      $retour = $platform->getNormalizedResults($itemType, $query, $limit);
      if ($retour == null) $retour = 0;
      
    } catch (PlatformTimeoutException $e) {
    
      $retour = null;
    
    }
    
    return $retour;

  }
  
  /*
   *
   * AGGREGATE FUNCTION
   *
   */
   
  public static function aggregate($query, $itemType, $limit = 999) {
  
    // Limits
    $limit = min(999,max(0,intval($limit)));
    
    // query decoding
    $query = trim(urldecode($query));
    
    $finalResult = null;
    
    // Actual search
    $platforms = API::$platforms;
    
    while (list($pId, $pObject) = each($platforms)) {
    
      if ( $pObject == false || 
         (!($pObject->isActiveForSearch()) && $itemType == 'track') || 
         (!($pObject->isActiveForAlbumSearch()) && $itemType == 'album')) {
        continue;
      }

      try {
        
        $result = $pObject->getNormalizedResults($itemType, $query, $limit);
        $nbResults = count($result);
        
        if ($finalResult == null) {
        
            $finalResult = $result;
            
            //Because the first results doesn't go through 'merge'
            for ($i=0; $i<$nbResults; $i++) {
        
              $finalResult[$i]['link'] = array(_TABLE_LINK_PREFIX.$pObject->getId() => $finalResult[$i]['link']);
          
            }
            
        } else {
          
          for ($i=0; $i<$nbResults; $i++) {
          
            // Merging the final result with the item returned
            $finalResult = API::merge($finalResult, $result[$i], $itemType, $pObject->getId());
          
          }
        
        }
        
      } catch (Exception $e) {}    
    
    }
    
    // Sorting this out
    function cmp($a, $b) {
      if ($a['score'] == $b['score'] ) {
        return 0;
      }
      return ($a['score']  < $b['score'] ) ? 1 : -1;
    } 
    uasort($finalResult, 'cmp');

    $finalResult = API::addTuneefyLinks($finalResult, $itemType);
    
    return $finalResult;

  }
  
  private static function merge($items, $newItemToMerge, $itemType, $platform){
  
    $nbItems = count($items);
    
    if ($itemType == 'album') {
      $key = 'album';
    } else if ($itemType == 'track'){
      $key = 'title';
    }
    
    // Let's run through items
    for ($k = 0; $k < $nbItems; $k++){
    
      if ($items[$k][$key] == $newItemToMerge[$key] && $items[$k]['artist'] == $newItemToMerge['artist']) {
      
        // That's the same item - YOUPI
        
        // If no album, let's merge that (for tracks only)
        if ($itemType != 'album' && $items[$k]['album'] == null && $newItemToMerge['album'] != null){
        
          $items[$k]['album'] = $newItemToMerge['album'];
          
        }
        // If no picture, let's merge that
        if ($items[$k]['picture'] == null && $newItemToMerge['picture'] != null){
        
          $items[$k]['picture'] = $newItemToMerge['picture'];
          
        }
        
        // Adding the link
        if ( !($items[$k]['link'][$platform]))
          $items[$k]['link'][_TABLE_LINK_PREFIX.$platform] = $newItemToMerge['link'];

        // Adding score
        $items[$k]['score'] += $newItemToMerge['score'];

      }
    }
    
    return $items;
  
  }

  private static function addTuneefyLinks($array, $itemType){

    foreach ($array as $key => $value) {

      $params = array( 'itemType' => ($itemType=='track')?_TABLE_TRACK:_TABLE_ALBUM,
                       'name' => $array[$key]['title'],
                       'artist' => $array[$key]['artist'],
                       'album' => $array[$key]['album'],
                       'image' => $array[$key]['picture'],
                       'redirect' => 1
                      );

      $platforms = $array[$key]['link'];

      $fullParams = http_build_query(array_merge($params, $platforms));

      $array[$key]['shareLink'] = _SITE_URL.'/include/share/share.php?'.$fullParams;

    }

    return $array;

  }
  
}
