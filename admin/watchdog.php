<?php

const _ACCESS = 1;
const _RESULT = 2;

  /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
  /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
  class Watchdog {
  
    // Tests
    protected $urls = array();
    protected $output = "";
    
    // Adding tests
    public function addTest($url, $type, $description, $result = NULL) {
     
      if ($type == _ACCESS) {
      
        $this->urls[] = array('url' => $url, 'description' => $description);

      } else if ($type == _RESULT) {
        
        $this->methods[] = array('url' => $url, 'description' => $description, 'result' => $result);
      
      }
    }
    
    public function checkDatabase($user, $password, $database) {
    
      $link = mysql_connect("localhost",$user,$password);

      if (!$link) {
      
        $this->log("DB Connection as '$user'", false, mysql_error());
        
      } else {
      
        $this->log("DB Connection as '$user'", true, "");
        $db_selected = mysql_select_db($database, $link);
        if (!$db_selected) {
          $this->log("DB Selection '$database'", false, mysql_error());
        } else {
          $this->log("DB Selection '$database'", true, "");
          $exe = mysql_query("SELECT * FROM items LIMIT 1",$link);
          if (!$exe || $exe == false ) {
            $this->log("DB Query", false, mysql_error());
          } else {
            $this->log("DB Query", true, "");
          }
          
        }
      
      }
    
    }
    
    // Helper : logger
    function log($text, $status, $error){
      $this->output .= "<li><span class=\"desc\">" . $text . " : " . "</span>";
      $this->output .= "<span class=\"". ($status?"success":"error") ."\">" . ($status?"OK":"FAIL ($error)") . "</span></li>";
    
    }
    
    // Running them
    public function run(){
      
      // We create a context for the beta here ...
      stream_context_set_default(
          array(
              'http' => array(
                  'method' => 'HEAD',
                  'header'  => "Authorization: Basic " . base64_encode("beta:tuneefy2011")
              )
          )
      );
      
      // ACCESS Tests
      $nbTests = count($this->urls);
      
      while (list($key, $val) = each($this->urls))
      {
        $headers = get_headers($val['url'], 1);
        
        if ($headers[0] == 'HTTP/1.1 200 OK') {
          $this->log($val['description'], true, "");
        } else {
          $this->log($val['description'], false, $headers[0]);
        }
  
      }
      
      stream_context_set_default(
          array(
              'http' => array(
                  'method' => 'GET',
                  'header'  => "Authorization: Basic " . base64_encode("beta:tuneefy2011")
              )
          )
      );
      
      while (list($key, $val) = each($this->methods))
      {
        $content = file_get_contents($val['url']);

        if ($content === $val['result']) {
          $this->log($val['description'], true, "");
        } else {
          $this->log($val['description'], false, htmlentities($content));
        }
      }
      
      return $this->output;
    }
  }
  /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
  /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

  require('../config.php');
  
  $medor = new Watchdog();
  
  // unitary tests

  // Tables are up ?
  $medor->checkDatabase($user, $password, $database);
    
  // Each platform is up
  $medor->addTest("http://www.deezer.com/fr/", _ACCESS, "Deezer");
  $medor->addTest("http://www.spotify.com/fr/", _ACCESS, "Spotify");
  $medor->addTest("http://www.last.fm", _ACCESS, "Last.fm");
  $medor->addTest("http://grooveshark.com", _ACCESS, "Grooveshark");
  $medor->addTest("http://www.jiwa.fr", _ACCESS, "Jiwa");
  $medor->addTest("http://soundcloud.com", _ACCESS, "Soundcloud");
  
  // Each page is up on Tuneefy
  $medor->addTest(_SITE_URL."", _ACCESS, "Home page");
  $medor->addTest(_SITE_URL."/home", _ACCESS, "Home page (alternate)");
  $medor->addTest(_SITE_URL."/trends", _ACCESS, "Trends page");
  $medor->addTest(_SITE_URL."/about", _ACCESS, "About page");
  $medor->addTest(_SITE_URL."/search/test", _ACCESS, "Basic Search");
  $medor->addTest(_SITE_URL."/?q=test&widget=42", _ACCESS, "Widget search");
  $medor->addTest(_SITE_URL."/js/min/tuneefy.min.js", _ACCESS, "JS : Minified JS");
  $medor->addTest(_SITE_URL."/js/dev/ControlFreak.class.js", _ACCESS, "JS : DEV ControlFreak");
  $medor->addTest(_SITE_URL."/js/lang/lang.js.php", _ACCESS, "JS : DEV lang");
  $medor->addTest(_SITE_URL."/js/dev/Model/Tuneefy.class.js", _ACCESS, "JS : DEV Model Tuneefy");
  $medor->addTest(_SITE_URL."/js/dev/Model/Tuneefy.links.class.js", _ACCESS, "JS : DEV Model Tuneefy links");
  $medor->addTest(_SITE_URL."/js/dev/Model/Tuneefy.track.class.js", _ACCESS, "JS : DEV Model Tuneefy track");
  $medor->addTest(_SITE_URL."/js/dev/UI/AlertUI.class.js", _ACCESS, "JS : DEV UI Alerts");
  $medor->addTest(_SITE_URL."/js/dev/UI/ResultRenderUI.class.js", _ACCESS, "JS : DEV UI ResultRender");
  $medor->addTest(_SITE_URL."/js/dev/UI/SearchUI.class.js", _ACCESS, "JS : DEV UI Search");
  $medor->addTest(_SITE_URL."/js/dev/UI/iphone-style.js", _ACCESS, "JS : DEV UI iphone-style");
  

  // Each platform respond to every search request and that the format is good
  
  // FIX ME
  
  // Tuneefy get algorithm works for every platform
  $medor->addTest(_SITE_URL."/include/share/get.php?id=0&query=".urlencode("crash+test"), _ACCESS, "API Access for Deezer");
  $medor->addTest(_SITE_URL."/include/share/get.php?id=1&query=".urlencode("crash+test"), _ACCESS, "API Access for Spotify");
  $medor->addTest(_SITE_URL."/include/share/get.php?id=2&query=".urlencode("crash+test"), _ACCESS, "API Access for Last.fm");
  $medor->addTest(_SITE_URL."/include/share/get.php?id=3&query=".urlencode("crash+test"), _ACCESS, "API Access for Grooveshark");
  $medor->addTest(_SITE_URL."/include/share/get.php?id=4&query=".urlencode("crash+test"), _ACCESS, "API Access for Jiwa");
  $medor->addTest(_SITE_URL."/include/share/get.php?id=5&query=".urlencode("crash+test"), _ACCESS, "API Access for Soundcloud");
    
  // Tuneefy query algorithm works for every pattern
  $permalink = "http://www.deezer.com/music/track/10236179";
  $result = '{"lookedUpPlatform":0,"query":"Daedelus+LA+Nocturne","lookedUpItem":{"name":"LA Nocturne","artist":"Daedelus","album":"Ninja Tune XX (Volume 1)","picture":"http:\/\/api.deezer.com\/2.0\/album\/935934\/image","link":"http:\/\/www.deezer.com\/music\/track\/10236179"}}';
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  
  $permalink = "http://www.deezer.com/fr/music/radiohead";
  $result = '{"lookedUpPlatform":0,"query":"radiohead","lookedUpItem":null}';
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);

  $permalink = "http://www.deezer.com/fr/music/rjd2/deadringer-144183";
  $result = '{"lookedUpPlatform":0,"query":"rjd2+deadringer","lookedUpItem":null}';
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  
  $result = '{"lookedUpPlatform":1,"query":"Kasabian+Test+Transmission","lookedUpItem":{"name":"Test Transmission","artist":"Kasabian","album":"Kasabian","picture":null,"link":"http:\/\/open.spotify.com\/track\/5jhJur5n4fasblLSCOcrTp"}}';
  $permalink = "http://open.spotify.com/track/5jhJur5n4fasblLSCOcrTp";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  $result = '{"lookedUpPlatform":1,"query":"Kasabian+Test+Transmission","lookedUpItem":{"name":"Test Transmission","artist":"Kasabian","album":"Kasabian","picture":null,"link":"http:\/\/open.spotify.com\/track\/5jhJur5n4fasblLSCOcrTp"}}';
  $permalink = "spotify:track:5jhJur5n4fasblLSCOcrTp";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);

  $result = '{"lookedUpPlatform":1,"query":"Sigur+Ros","lookedUpItem":null}';
  $permalink = "http://open.spotify.com/artist/6UUrUCIZtQeOf8tC0WuzRy";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  $permalink = "spotify:artist:6UUrUCIZtQeOf8tC0WuzRy";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  
  $result = '{"lookedUpPlatform":1,"query":"Sigur+Ros+Inni","lookedUpItem":null}';
  $permalink = "http://open.spotify.com/album/2bRcCP8NYDgO7gtRbkcqdk";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  $permalink = "spotify:album:2bRcCP8NYDgO7gtRbkcqdk";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  
  $result = '{"lookedUpPlatform":2,"query":"The+Clash+London+Calling","lookedUpItem":{"name":"London Calling","artist":"The Clash","album":"London+Calling","picture":null,"link":"http:\/\/www.last.fm\/music\/The+Clash\/London+Calling\/London+Calling"}}';  
  $permalink = "http://www.last.fm/music/The+Clash/London+Calling/London+Calling";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  
  $result = '{"lookedUpPlatform":2,"query":"The+Clash+London+Calling","lookedUpItem":null}';
  $permalink = "http://www.last.fm/music/The+Clash/London+Calling";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  
  $result = '{"lookedUpPlatform":2,"query":"Sex+Pistols","lookedUpItem":null}';
  $permalink = "http://www.lastfm.fr/music/Sex+Pistols";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  /*
  $result ='{"type":4,"query":"Crash+Test+Dummies+Mmm+Mmm+Mmm+Mmm","track":{"name":"Mmm Mmm Mmm Mmm","artist":"Crash Test Dummies","album":"God Shuffled His Feet","image":null,"link":"http:\/\/www.jiwa.fr\/#track\/52575"}}';
  $permalink = "http://www.jiwa.fr/#track/52575";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  
  $result = '{"type":4,"query":"Justice","track":null}';
  $permalink = "http://www.jiwa.fr/#artist/3814";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  
  $result = '{"type":4,"query":"Road+to+Recovery+Midnight+Juggernauts","track":null}';
  $permalink = "http://www.jiwa.fr/#album/328122";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  
  $result = '{"type":4,"query":"The+Who+Baba+O\'Riley","track":{"name":"Baba O\'Riley","artist":"The Who","album":"Who\'s Next","image":null,"link":"http:\/\/www.jiwa.fr\/#track\/400388"}}';
  $permalink = "http://jiwa.fr/track/The-Who-749/Who-s-Next-70366/Baba-O-Riley-400388.html";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  */
  $result = '{"lookedUpPlatform":3,"query":"Impeccable+Blahs","lookedUpItem":null}';
  $permalink = "http://grooveshark.com/album/Impeccable+Blahs/1529354";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  
  $result = '{"lookedUpPlatform":3,"query":"Say+Hi+To+Your+Mom","lookedUpItem":null}';
  $permalink = "http://grooveshark.com/artist/Say+Hi+To+Your+Mom/401373";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  
  $result = '{"lookedUpPlatform":5,"query":"radiohead+codex+deadmau5+cover","lookedUpItem":null}';
  $permalink = "http://soundcloud.com/fuckmylife/radiohead-codex-deadmau5-cover";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  
  $result = '{"lookedUpPlatform":6,"query":"Radiohead+Everything","lookedUpItem":{"name":"Everything","artist":"Radiohead","album":null,"picture":null,"link":"http:\/\/hypem.com\/item\/1g079"}}';
  $permalink = "http://hypem.com/item/1g079/";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  
  $result = '{"lookedUpPlatform":7,"query":"2+Chainz+Spend+It","lookedUpItem":{"name":"Spend It","artist":"2 Chainz","album":null,"picture":"http:\/\/i.ytimg.com\/vi\/_FOyHhU0i7k\/1.jpg","link":"http:\/\/youtube.com\/watch?v=_FOyHhU0i7k"}}';
  $permalink = "http://www.youtube.com/watch?v=_FOyHhU0i7k";
  $medor->addTest(_SITE_URL."/include/share/query.php?str=".urlencode($permalink), _RESULT, "Query for permalink [$permalink]",$result);
  

  // Run the whole test
  $output = $medor->run();

  echo $output;
  