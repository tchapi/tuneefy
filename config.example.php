<?php
  
  // Defines GA Tracker (http://analytics.google.com)
  define('_GOOGLE_GA_TRACKER', "0"); 
  
  // Desactivates the trends access
  define('_DESACTIVATE_TRENDS', true);
  
  // Desactivates playlists
  define('_DESACTIVATE_PLAYLISTS', true);
  
  // Desactivates dev mode
  define('_DESACTIVATE_DEV', true);

  // Base for Id <-> GUID
  define('_BASE_MULTIPLIER', 123456789);

  // Allow USERVOICE Feedback
  define('_USERVOICE_ENABLED', true);
  
  // Social
  define('_FB_ADMIN', "XXXXXXX"); 
  define('_FB_APP_ID', "YYYYYYYYYY");
  define('_FB_APP_SECRET', "ZZZZZZZZZZZ");
  define('_FB_PAGE_ID', "UUUUUUUUUU");
  define('_GPLUS_PUBLISHER_ID', "https://plus.google.com/TTTTTTTTTTT");

  // Site base url :
  define('_SITE_URL', "http://www.tuneefy-on-git.com");
  define('_API_URL', "http://api.tuneefy-on-git.com");
  define('_ADMIN_URL', "http://admin.tuneefy-on-git.com");
  
  // Mails
  define('_SMTP_SERVER', "smtp.tuneefy-on-git.com");
  define('_SMTP_MAIL', "sending@tuneefy-on-git.com");
  define('_SMTP_PASSWORD', "foobar");
  define('_CONTACT_MAIL', "contact@tuneefy-on-git.com");
  define('_TEAM_MAIL', "team@tuneefy-on-git.com");

  // Language
  $_LANG = "en";

  // Database info
  $user = "UUUUUUUUUU";
  $password = "XXXXXXXXXXXX";
  $database = "YYYYYYYYYYYY";
  
  // Platforms options
  $options['DEEZER']      = array("key" => null, "secret" => null, "default" => true, "search" => true, "album_search" => false, "lookup" => true, "order" => 1);
  $options['SPOTIFY']     = array("key" => null, "secret" => null, "default" => true, "search" => true, "album_search" => false, "lookup" => true, "order" => 2);
  $options['LASTFM']      = array("key" => null, "secret" => null, "default" => true, "search" => true, "album_search" => false, "lookup" => true, "order" => 4);
  $options['GROOVESHARK'] = array("key" => null, "secret" => null, "default" => true, "search" => true, "album_search" => false, "lookup" => true, "order" => 5);
  // DEPRECATED $options['JIWA'] = array("key" => null, "secret" => null, "default" => false, "search" => false, "album_search" => false, "lookup" => false);
  $options['SOUNDCLOUD']  = array("key" => null, "secret" => null, "default" => false, "search" => true, "album_search" => false, "lookup" => true, "order" => 8);
  
  $options['HYPEMACHINE'] = array("key" => null, "secret" => null, "default" => false, "search" => false, "album_search" => false, "lookup" => true, "order" => 9);
  $options['YOUTUBE']     = array("key" => null, "secret" => null, "default" => false, "search" => false, "album_search" => false, "lookup" => false, "order" => 6);
  $options['MIXCLOUD']    = array("key" => null, "secret" => null, "default" => false, "search" => false, "album_search" => false, "lookup" => false, "order" => 10);
  $options['MOG']         = array("key" => null, "secret" => null, "default" => false, "search" => false, "album_search" => false, "lookup" => false, "order" => 11);
  $options['RDIO']        = array("key" => null, "secret" => null, "default" => false, "search" => false, "album_search" => false, "lookup" => false, "order" => 7);
  $options['ITUNES']      = array("key" => null, "secret" => null, "default" => false, "search" => false, "album_search" => false, "lookup" => false, "order" => 12);
  $options['ECHONEST']    = array("key" => null, "secret" => null, "default" => false, "search" => false, "album_search" => false, "lookup" => false, "order" => 13);
  $options['QOBUZ']       = array("key" => null, "secret" => null, "default" => true, "search" => false, "album_search" => false, "lookup" => false, "order" => 3);
  $options['XBOX']        = array("key" => null, "secret" => null, "default" => true, "search" => true, "album_search" => true, "lookup" => true, "order" => 14);
  
  // Watchdog Tests
  define('_API_KEY', "12345678");
  define('_API_SECRET', "12345678");
  define('_WATCHDOG_MAIL', "test@example.com");

  /** Absolute path to the Tuneefy directory. */
  if ( !defined('_PATH') )
        define('_PATH', dirname(__FILE__) . '/');
        
  require(_PATH.'include/constants.php');