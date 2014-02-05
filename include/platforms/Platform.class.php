<?php

/* *************************************************** */
/*                                                     */
/*                 CUSTOM EXCEPTION CLASS              */
/*                                                     */
/* *************************************************** */

class PlatformTimeoutException extends Exception { }


/* *************************************************** */
/*                                                     */
/*                    PLATFORM CLASS                   */
/*                                                     */
/* *************************************************** */

define('_LISTEN', 0);
define('_BUY', 1);
define('_META', 2);

abstract class Platform {

  /* *************************************** */
  // Credentials
  protected $name;
  protected $safe_name;
  protected $color;
  protected $id = null;
  
  // Utility
  protected $type = _LISTEN;
  
  // For authentication
  protected $api_key;
  protected $needsOAuth = false;
  protected $api_secret = null;
  
  // API endpoint
  protected $api_endpoint;
  protected $api_method;

  // Query Track
  protected $query_endpoint;
  protected $query_term;
  protected $query_options;

  // Query Album
  protected $query_album_endpoint;
  protected $query_album_term;
  protected $query_album_options;

  // Lookup 
  protected $lookup_endpoint;
  protected $lookup_term;
  protected $lookup_options;

  // Retrieval permalink
  protected $track_permalink;
  protected $album_permalink;
  
  // Search and lookup Behavior
  protected $isDefault = false;
  protected $isActiveForSearch = false;
  protected $isActiveForAlbumSearch = false;
  protected $isActiveForLookup = false;
  
  // Helper
  protected $REGEX_FULLSTRING = "([a-zA-Z0-9%\+-\s\_\.]*)";
  /* *************************************** */
  
  // IMPLEMENTED IN CHILD CLASSES
  abstract public function getNormalizedResults($itemType, $result, $limit);
  abstract public function hasPermalink($permalink);
  abstract public function lookupPermalink($permalink);

  // GENERIC GETTERS AND SETTERS
  public function getName(){ return $this->name; }
  public function getSafeName(){ return $this->safe_name; }
  public function getColor(){ return $this->color; }
  public function getId(){ return $this->id; }
  public function isDefault(){ return $this->isDefault; }
  public function isActiveForSearch(){ return $this->isActiveForSearch; }
  public function isActiveForAlbumSearch(){ return $this->isActiveForAlbumSearch; }
  public function isActiveForLookup(){ return $this->isActiveForLookup; }
  
  // GENERIC
  private function constructCall($type, $item){

    if ($type == 'track') {
      $endpoint = $this->query_endpoint;
      $term = $this->query_term;
      $options = $this->query_options;
    } else if ($type == 'album') {
      $endpoint = $this->query_album_endpoint;
      $term = $this->query_album_term;
      $options = $this->query_album_options;
    } else if ($type == 'lookup') {
      $endpoint = $this->lookup_endpoint;
      $term = $this->lookup_term;
      $options = $this->lookup_options;
    }
    
    // Let's construct the url
    $api_url_full = $this->api_endpoint.$endpoint;
    $data = null;

    // Let's construct the GET or POST data
    if ($term == null) { // Obviously, $method == GET then
      $api_url_full = sprintf($api_url_full,urlencode($item));
      if ($options != null) $data = $options;
    } else {
      if ($options != null)
        $lookup_data = $options + array($term => $item);
      else
        $lookup_data = array($term => $item);
      $data = $lookup_data;
    }
    
    return array( 'url' => $api_url_full, 'data' => $data, 'method' => ($this->api_method) );

  }

  protected function callPlatform($type, $query) {

    $call = $this->constructCall($type, $query);
    
    return $this->makeCall($call,$this->needsOAuth);
  
  }
  
  protected static function unchunkHttp11($data) {
    $fp = 0;
    $outData = "";
    while ($fp < strlen($data)) {
        $rawnum = substr($data, $fp, strpos(substr($data, $fp), "\r\n") + 2);
        $num = hexdec(trim($rawnum));
        $fp += strlen($rawnum);
        $chunk = substr($data, $fp, $num);
        $outData .= $chunk;
        $fp += strlen($chunk);
    }
    return $outData;
  }

  protected function makeCall($call, $needsOAuth){
    
    // In case we need OAuth (simple signed request)
    if ($needsOAuth){
    
      // We add the signature to the request data
      $consumer = new OAuthConsumer($this->api_key, $this->api_secret, null);
      $req = OAuthRequest::from_consumer_and_token($consumer, null, $call['method'], $call['url'], $call['data']);
      $hmac_method = new OAuthSignatureMethod_HMAC_SHA1();
      $req->sign_request($hmac_method, $consumer, null);

      $call['data'] = $req->get_parameters();
            
    }   
    
    // Build the query for params
    $params = null;
    if ($call['data'] != null) {
      if ($call['method'] == 'POST_JSON') {
        $params = $call['data'];
      } else {
        $params = http_build_query($call['data']);
      }
    }
    
    // Depending on the method, we make the request
    if ($call['method'] == 'GET') {

      $opts = array(
          'http' => array(
              'method'  => 'GET',
              'timeout' => 3,
              'header'  => "Content-Type: charset=UTF-8\r\n".
                           "Connection: close"
          )
      );

      $context = stream_context_create($opts); 
      $result = @file_get_contents($call['url'].'?'.$params,0,$context);

    } else if ($call['method'] == 'POST' || $call['method'] == 'POST_JSON') {

      // Parse the given URL
      $url = parse_url($call['url']);

      // Extract host and path:
      $host = $url['host'];
      $path = $url['path'];
        if (isset($url['query']) &&  $url['query']) $path .= '?'.$url['query'];

      if ($url['scheme'] == 'https') {
        // Open a socket connection on port 80 - timeout: 3 sec
        $fp = fsockopen('ssl://'.$host, 443, $errno, $errstr, 3);
      } else {
        // Open a socket connection on port 80 - timeout: 3 sec
        $fp = fsockopen($host, 80, $errno, $errstr, 3);
      }

      if ($fp){

        // Send the request headers:
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded; charset=utf-8\r\n");
        fputs($fp, "Content-length: ". strlen($params) ."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $params);

        $result = ''; 
        while(!feof($fp)) {
          // Receive the results of the request
          $result .= fgets($fp, 1024);
        }

        $mustUnchunk = false;
        if (strpos(strtolower($result), "transfer-encoding: chunked") !== FALSE) {
            $mustUnchunk = true;
        }

        // Split the result header from the content
        $result = explode("\r\n\r\n", $result, 2);
        $result = isset($result[1]) ? $result[1] : null;

        if ($mustUnchunk === true) {
          $result = self::unchunkHttp11($result);
        }
        
      } else { 
        $result = null;
      }

      // Close the socket connection:
      fclose($fp);

    }

    // Just in case, remove the BOM (THANKS XBOX I HATE YOU)
    $bom = pack('H*','EFBBBF');
    $result = preg_replace("/^$bom/", '', $result);

    if ($result == null) 
      throw new PlatformTimeoutException();

    return json_decode($result);

  }

}
