<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Product"
      xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# tuneefy: http://ogp.me/ns/fb/tuneefy#">

<?php // Encoding // ?>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
<?php // META standards // ?>
  <meta name="description" content="<?php $i18n->description; ?>" />
  <meta name="keywords" content="<?php $i18n->tags; ?>" />
  <meta content="noarchive,noindex" name="robots" />
 
<?php // ANALYTICS // ?>
  <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo _GOOGLE_GA_TRACKER; ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

  </script>
  

<?php // FAVICON ?>
  <link rel="icon" type="image/png" href="<?php echo _SITE_URL; ?>/img/favicon.png" />
<!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico" /><![endif]-->

<?php // Style // ?>
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo _SITE_URL; ?>/css/reset.css" />
<?php if ($mobile) { ?>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo _SITE_URL; ?>/css/mobile.css" />
  <meta content="yes" name="apple-mobile-web-app-capable" />
  <link href="<?php echo _SITE_URL; ?>/img/social.png" rel="apple-touch-icon" />
  <meta content="width=device-width, user-scalable=no" name="viewport" />
<?php } else { ?>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo _SITE_URL; ?>/css/general.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo _SITE_URL; ?>/css/style.css" />
<!--[if lte IE 9]>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo _SITE_URL; ?>/css/ie.css" />
<![endif]-->
<?php } ?>

</head>
<body <?php if ($mobile) echo 'onload="window.scrollTo(0, 1)"'; ?> class="Bcolor"> 

<div id="wrapper" class="light">

  <div id="content-wrapper" class="full">
    <div class="wrapSmall clear bdBot" id="content">
      <div id="taglineCenter" class="bdBot">
        <a href="<?php echo _SITE_URL; ?>"><h1 class="color"><img src="<?php echo _SITE_URL; ?>/img/logo.png" width="267" height="133" alt="<?php $i18n->general_title; ?>"/></h1></a>
        <p class="txtS"><?php $i18n->tagline; ?></p>
      </div>