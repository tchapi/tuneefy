<?php

/* *************************************************** */
/*                                                     */
/*                         STATIC                      */
/*                      OAUTH HELPER                   */
/*                                                     */
/* *************************************************** */

class OAuth {

  public static function signRequest($public_key, $private_secret, $token, $method, $url, $parameters){
  
    $http_request_array = OAuth::createRequestArray($public_key, $parameters);
    $signature = OAuth::createSignature($private_secret, $token, $method, $url, $http_request_array);

    // And we create the signature from the base_string and the key
    $signature_array = array( "oauth_signature" => $signature);
    
    // Now that we have the signature, we create the parameters normally
    return array_merge($http_request_array, $signature_array);
    
  }
  
  public static function createSignature($private_secret, $token, $method, $url, $http_request_array){
  
    // lacking HTTP build query with PHP_QUERY_RFC3986 in PHP 5.3      
    $base_string = strtoupper($method)."&".urlencode($url)."&".urlencode(str_replace('+', '%20', http_build_query($http_request_array)));
    
    // We create the secret key.
    if (!$token)
      $key = $private_secret;
    else
      $key = $private_secret."&".$token;
    
    return base64_encode(hash_hmac('SHA1', $base_string, $key, true));
    
  }
  
  public static function createRequestArray($public_key, $parameters){
  
    // oauth_consumer_key
    // Your application's API key
    $consumer_key = array( "oauth_consumer_key" => $public_key);
    
    // oauth_nonce
    // A randomly-generated string that is unique to each API call. This, along with the timestamp, is used to prevent replay attacks.
    $nonce = array("oauth_nonce" => time());

    // oauth_signature_method
    // The cryptographic method used to sign the call. Vimeo only supports HMAC-SHA1.
    $sig_method = array("oauth_signature_method" => "HMAC-SHA1" );

    // oauth_timestamp
    // The number of seconds that have elapsed since midnight, January 1, 1970, also known as UNIX time. 
    $timestamp = array("oauth_timestamp" => time() );

    // oauth_version
    // You don't need to include this, but if you do, it must be 1.0. 
    $version = array ("oauth_version" => "1.0");
  
    $http_request_array = array_merge($consumer_key,$nonce,$sig_method,$timestamp,$version,$parameters);
    
    // Sorts the request params
    ksort($http_request_array);
    
    return $http_request_array;
  }
  
  public static function checkRequest($signature, $public_key, $private_secret, $token, $method, $url, $parameters) {
    
    // Creates the correct signature
    $correct_signature = OAuth::createSignature($private_secret, $token, $method, $url, $parameters);
    
    // Each request is allowed 5 seconds to live
    if (intval($parameters['oauth_timestamp']) < intval(time()) - 5)
      return false;
      
    // Check for zero length, although unlikely
    if (strlen($correct_signature) == 0 || strlen($signature) == 0) {
      return false;
    }

    // Quickly checks length in case
    if (strlen($correct_signature) != strlen($signature)) {
      return false;
    }

    // Avoid a timing leak with a (hopefully) time insensitive compare
    $result = 0;
    for ($i = 0; $i < strlen($signature); $i++) {
      $result |= ord($correct_signature{$i}) ^ ord($signature{$i});
    }

    return $result == 0;
    
  }

}
