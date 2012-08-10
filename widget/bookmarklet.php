<?php 

  require('../config.php');
  
// Ordre des remplacements
$str     = file_get_contents("bookmarklet.js");
$order   = array("\r\n", "\n", "\r", " ");
$replace = '';

// Traitement.
$minified = str_replace($order, $replace, $str);
$minified = str_replace("%s%",_SITE_URL, $minified);

?><html>
    <head>
      <title>Tuneefy it !</title>
    </head>
    <body>
    Drag me in your bookmarks bar : <a href="<?php echo $minified; ?>" style="background: lightgrey; border: 1px solid grey; padding: 5px;">Tuneefy it!</a>
    </body>
</html>