<?php

require_once(dirname(__FILE__) . "/../../config.php");
require_once(_PATH . "include/oauth/OAuth.class.php");
require_once(_PATH . "include/mail/mailHelper.class.php");
require_once(_PATH . "admin/watchdog/watchdog.conf");

const _CHECK_200_STATUS = 1;
const _CHECK_API_RESPONSE = 2;

$data = array(
  'user' => $user,
  'password' => $password,
  'database' => $database,
  'platformUrls' => $platformUrls,
  'pages' => $pages,
  'apiSearch' => $apiSearch,
  'apiLookup' => $apiLookup,
  'lookup' => $lookup
  );

// --------------------------

class WatchdogTest {

  private $url, $type, $signed, $description, $result;

  // --- static helper
  private static function &array_get_nested_value(array &$array, array $parents, &$key_exists = NULL) {
    $ref = &$array;
    foreach ($parents as $parent) {
      if (is_array($ref) && array_key_exists($parent, $ref)) {
        $ref = &$ref[$parent];
      }
      else {
        $key_exists = FALSE;
        $null = NULL;
        return $null;
      }
    }
    $key_exists = TRUE;
    return $ref;
  }
  // ---

  public function __construct($url, $type, $description, $signed, $result = null){

    $this->type = $type;
    $this->signed = $signed;
    $this->description = $description;
    if ($this->type === _CHECK_API_RESPONSE ) $this->result = $result; // Array

    if ($this->signed) {
      $this->url = $this->sign($url);
    } else {
      $this->url = $url;
    }

  }

  public function getDescription(){
    return $this->description;
  }


  public function getUrl(){
    return $this->url;
  }

  private function sign($url){

    // URLS tests
    stream_context_set_default(array('http' => array('method' => 'GET')));

    $hmac_method = new OAuthSignatureMethod_HMAC_SHA1();
    $test_consumer = new OAuthConsumer(_API_KEY, _API_SECRET, NULL);

    $parsed = parse_url($url);
    $params = array();
    parse_str($parsed['query'], $params);

    $req_req = OAuthRequest::from_consumer_and_token($test_consumer, NULL, "GET", $url, $params);
    $req_req->sign_request($hmac_method, $test_consumer, NULL);

    return $req_req->to_url();

  }

  private function check200OKStatus(){

      // Gets the header
      $raw = get_headers($this->url, 1);
      $OK = preg_match('/HTTP\/1.(0|1)\ 20(0|4)\ (OK|No\ Content)/', $raw[0]);

      if ($OK) {
        return array('check' => true);
      } else {
        return array('check' => false, 'error' => $raw[0]);
      }

  }

  private function checkAPIResponse(){

      $raw = file_get_contents($this->url);

      // We decode from JSON
      $jsonResult = json_decode($raw, true);
      
      if ($jsonResult == null) {
        return array('check' => false, 'error' => "Empty call response : ($raw)");
      }

      // An then we compare each item
      // Everything in $result must be in $jsonResult
      foreach ($this->result as $key => $expectedValue) {
        $key_exists = NULL;
        $jsonValue = self::array_get_nested_value($jsonResult, explode('.',$key), $key_exists);
        if ($key_exists) {
          if ($jsonValue !== $expectedValue){
            return array('check' => false, 'error' => "Expected '$expectedValue' but got '$jsonValue' for key '$key'");
          }
        } else {
          return array('check' => false, 'error' => "Key '$key' not present in API response");
        }
      }

      return array('check' => true);

  }

  public function run(){

    if ($this->type === _CHECK_200_STATUS) {
   
      return $this->check200OKStatus();

    } else if ($this->type === _CHECK_API_RESPONSE) {
    
      return $this->checkAPIResponse();
    }

  }

}


class Watchdog {

  private $tests = array();
  private $data = null;
  private $verbose = true;

  private $status = true;
  private $output = "";
  private $lastRunTime = 0;
  
  public function __construct($data, $verbose){
    $this->data = $data;
    $this->verbose = $verbose;
  }

  // Adding tests
  public function addTest($url, $type, $description, $signed = false, $result = NULL) {
   
    $test= new WatchdogTest($url, $type, $description, $signed, $result);

    $this->tests[] = $test;
    $result = $test->run();

    if ($result['check'] && $this->verbose) {
      $this->log($test->getDescription(), true, $test->getUrl());
    } else if (!$result['check']) {
      $this->status = false;
      $this->log($test->getDescription(), false, $test->getUrl(), $result['error']);
    }
    
  }
  
  // Checking DB
  public function checkDatabase() {
  
    $link = mysql_connect("localhost",$this->data['user'],$this->data['password']);

    if (!$link) {
    
      $this->log("DB Connection as '$user'", false, mysql_error());
      
    } else {
    
      if ($this->verbose) $this->log("DB Connection as '".$this->data['user']."'", true, "");

      $db_selected = mysql_select_db($this->data['database'], $link);

      if (!$db_selected) {

        $this->log("DB Selection '$database'", false, mysql_error());

      } else {

        if ($this->verbose) $this->log("DB Selection '".$this->data['database']."'", true, "");

        $exe = mysql_query("SELECT * FROM items LIMIT 1",$link);

        if (!$exe || $exe == false ) {

          $this->log("DB Query", false, mysql_error());

        } else {

          if ($this->verbose) $this->log("DB Query", true, "");

        }
        
      }
    
    }
  
  }
  
  // Helper : logger
  private function log($text, $status, $url = null, $error = null){
    $this->output .= "<li><span class=\"desc\">" . $text . ($url?" (<a href='". $url . "' target=_blank>url</a>)":"") . " : " . "</span>";
    $this->output .= "<span class=\"". ($status?"success":"error") ."\">" . ($status?"OK":"FAIL ($error)") . "</span></li>";
  }

  public function run(){

    $time_pre = microtime(true);

    // Basics
    $platforms = API::getPlatforms();

    // >> TEST ----------------------------------------
    // Tables are up
    $this->checkDatabase();

    // >> TEST ----------------------------------------
    // Each platform is up
    foreach ($this->data['platformUrls'] as $name => $url) {
      $this->addTest($url, _CHECK_200_STATUS, $name);
    }

    // >> TEST ----------------------------------------
    // Each page is up on Tuneefy
    foreach ($this->data['pages'] as $name => $url) {
      $this->addTest($url, _CHECK_200_STATUS, $name);
    }

    // >> TEST ----------------------------------------
    // The API respond to search request from every platform and the format is good
    //  ex : http://api.tuneefy.local/search?q=radiohead+creep&platform=0&type=track&limit=2&alt=json
    while (list($pId, $pObject) = each($platforms)) {
      if ($pObject->isActiveForSearch()){
        $this->addTest(sprintf($this->data['apiSearch']['url'],urlencode("radiohead"),$pObject->getId()), _CHECK_200_STATUS, sprintf($this->data['apiSearch']['name'],$pObject->getName()), true);
      } else {
        $this->addLog(">> Plaform ".$pObject->getName()." is not active");
      }
    }

    // >> TEST ----------------------------------------
    // The API returns correct permalinks resolutions
    foreach ($this->data['lookup'] as $instance) {
      $this->addTest(sprintf($this->data['apiLookup']['url'],urlencode($instance['search'])), _CHECK_API_RESPONSE, sprintf($this->data['apiLookup']['name'],$instance['search']), true, $instance['result']);
    }

    $this->lastRunTime = (microtime(true) - $time_pre);
    return $this->getStatus();

  }

  public function addLog($text){
    $this->output .= "<li><span class=\"desc other\">" . $text . "</span>";
  }

  public function getOutput() {
    return $this->output;
  }

  public function getStatus() {
    return $this->status;
  }

  public function getLastRunTime() {
    return $this->lastRunTime;
  }

}

// Run
$medor = new Watchdog($data, true);
echo "Running ... ";

for ($i=1; $i < 5; $i++) { 
  $medor->run();
  if ($medor->getStatus() == true) break;
}

if ($medor->getStatus() != true) {
  
  echo "FAILED.";

  // Send mail  
  $message = sprintf("Last failed watchdog run took %d seconds.\r\n\r\n", $medor->getLastRunTime());
  $message .= $medor->getOutput();
  
  // Envoi
  $status = MailerHelper::sendWatchdogMail($message);

} else {
  echo "OK.";
}

echo sprintf(" (Run took %d seconds)\r\n", $medor->getLastRunTime());
