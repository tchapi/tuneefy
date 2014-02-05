<?php

date_default_timezone_set('Europe/Paris');

// Results limit
define('_LIMIT', 100);

// Iframe mode
define('_IFRAME', "<iframe src='%s&embed' allowtransparency='yes' border='no' frameBorder='0' scrolling='0' width='350' height='150' ></iframe>");

// Platforms
define('_TABLE_LINK_PREFIX', "link");

define('_TABLE_TRACK', 0);
define('_TABLE_ALBUM', 1);

// Oauth helper
require_once(_PATH.'include/oauth/OAuth.class.php');
// Abstract Platform class
require_once(_PATH.'include/platforms/Platform.class.php');
// Children classes - implement Platform.class.php
require_once(_PATH.'include/platforms/Platforms.class.php');

// API (for get and query)
require_once(_PATH.'include/api/API.class.php');
//API::setPlatforms($platforms);

// For reference : Platform::__construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $order)
//                 API::addPlatform($platform, $id);
define('_DEEZER', 0);
API::addPlatform(new DEEZER($options['DEEZER']['key'],
                            $options['DEEZER']['secret'],
                            $options['DEEZER']['default'],
                            $options['DEEZER']['search'],
                            $options['DEEZER']['album_search'],
                            $options['DEEZER']['lookup'],
                            _DEEZER),
                            $options['DEEZER']['order']);

define('_SPOTIFY', 1);
API::addPlatform(new SPOTIFY($options['SPOTIFY']['key'], 
                             $options['SPOTIFY']['secret'],
                             $options['SPOTIFY']['default'], 
                             $options['SPOTIFY']['search'],
                             $options['SPOTIFY']['album_search'],
                             $options['SPOTIFY']['lookup'],
                             _SPOTIFY),
                             $options['SPOTIFY']['order']);

define('_LASTFM', 2);
API::addPlatform(new LASTFM($options['LASTFM']['key'],
                            $options['LASTFM']['secret'],
                            $options['LASTFM']['default'],
                            $options['LASTFM']['search'],
                            $options['LASTFM']['album_search'],
                            $options['LASTFM']['lookup'],
                            _LASTFM),
                            $options['LASTFM']['order']);

define('_GROOVESHARK', 3);
API::addPlatform(new GROOVESHARK($options['GROOVESHARK']['key'],
                                 $options['GROOVESHARK']['secret'],
                                 $options['GROOVESHARK']['default'],
                                 $options['GROOVESHARK']['search'],
                                 false,
                                 $options['GROOVESHARK']['lookup'],
                                 _GROOVESHARK),
                                 $options['GROOVESHARK']['order']);

/* DEPRECATED */
define('_JIWA', 4);
API::addPlatform(new JIWA(null, null, false, false, false, false, _JIWA), 9999);
/* ********** */

define('_SOUNDCLOUD', 5);
API::addPlatform(new SOUNDCLOUD($options['SOUNDCLOUD']['key'],
                                $options['SOUNDCLOUD']['secret'],
                                $options['SOUNDCLOUD']['default'],
                                $options['SOUNDCLOUD']['search'],
                                false,
                                $options['SOUNDCLOUD']['lookup'],
                                _SOUNDCLOUD),
                                $options['SOUNDCLOUD']['order']);

define('_HYPEMACHINE', 6);
API::addPlatform(new HYPEMACHINE($options['HYPEMACHINE']['key'],
                                 $options['HYPEMACHINE']['secret'],
                                 $options['HYPEMACHINE']['default'],
                                 $options['HYPEMACHINE']['search'],
                                 false,
                                 $options['HYPEMACHINE']['lookup'],
                                 _HYPEMACHINE),
                                 $options['HYPEMACHINE']['order']);

define('_YOUTUBE', 7);
API::addPlatform(new YOUTUBE($options['YOUTUBE']['key'],
                             $options['YOUTUBE']['secret'],
                             $options['YOUTUBE']['default'],
                             $options['YOUTUBE']['search'],
                             false,
                             $options['YOUTUBE']['lookup'],
                             _YOUTUBE),
                             $options['YOUTUBE']['order']);

define('_MIXCLOUD', 8);
API::addPlatform(new MIXCLOUD($options['MIXCLOUD']['key'],
                              $options['MIXCLOUD']['secret'],
                              $options['MIXCLOUD']['default'],
                              $options['MIXCLOUD']['search'],
                              false,
                              $options['MIXCLOUD']['lookup'],
                              _MIXCLOUD),
                              $options['MIXCLOUD']['order']);

define('_MOG', 9);
API::addPlatform(new MOG($options['MOG']['key'], $options['MOG']['secret'], false, false, false, $options['MOG']['lookup'], _MOG), $options['MOG']['order']); // no search for MOG

define('_RDIO', 10);
API::addPlatform(new RDIO($options['RDIO']['key'],
                          $options['RDIO']['secret'],
                          $options['RDIO']['default'],
                          $options['RDIO']['search'],
                          $options['RDIO']['album_search'],
                          $options['RDIO']['lookup'],
                          _RDIO),
                          $options['RDIO']['order']);

  
define('_QOBUZ', 13);
API::addPlatform(new QOBUZ($options['QOBUZ']['key'],
                           $options['QOBUZ']['secret'],
                           $options['QOBUZ']['default'],
                           $options['QOBUZ']['search'],
                           $options['QOBUZ']['album_search'],
                           $options['QOBUZ']['lookup'],
                           _QOBUZ),
                           $options['QOBUZ']['order']);

define('_XBOX', 14);
API::addPlatform(new XBOX($options['XBOX']['key'],
                              $options['XBOX']['secret'],
                              $options['XBOX']['default'],
                              $options['XBOX']['search'],
                              $options['XBOX']['album_search'],
                              $options['XBOX']['lookup'],
                              _XBOX),
                              $options['XBOX']['order']);

// Utilities
define('_ITUNES', 11);
API::addPlatform(new ITUNES($options['ITUNES']['key'],
                            $options['ITUNES']['secret'],
                            $options['ITUNES']['default'],
                            $options['ITUNES']['search'],
                            $options['ITUNES']['album_search'],
                            false,
                            _ITUNES),
                            $options['ITUNES']['order']); // no lookup for iTunes

define('_ECHONEST', 12);
API::addPlatform(new ECHONEST($options['ECHONEST']['key'],
                              $options['ECHONEST']['secret'],
                              $options['ECHONEST']['default'],
                              $options['ECHONEST']['search'],
                              false,
                              $options['ECHONEST']['lookup'],
                              _ECHONEST),
                              $options['ECHONEST']['order']);


// This function returns a correct url
function web($url){

  if (substr($url,0,13) == "spotify:track") {
    return str_replace ("spotify:track:","http://open.spotify.com/track/", $url);
  } else if (substr($url,0,13) == "spotify:album") {
    return str_replace ("spotify:album:","http://open.spotify.com/album/", $url);
  } else
    return $url;

}

// This function sanitizes strings
function sanitize($string){
  return strtolower(preg_replace('/[^\w\-]+/u', '-', $string));
}

// This function escapes " characters
function esc($string){
  return addcslashes($string, '"');
}

// Ellipsis function
function ellipsis($text, $max=100, $append='&hellip;')
{
  if (strlen($text) <= $max) return $text;
  $out = substr($text,0,$max);
  if (strpos($text,' ') === FALSE) return $out.$append;
  return preg_replace('/\w+$/','',$out).$append;
}
