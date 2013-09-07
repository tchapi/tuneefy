<!DOCTYPE html>
<?php
  // Type of schema
  if ($action == 'album'){
    $schemaType = 'MusicAlbum';
  } elseif ($action == 'track'){
    $schemaType = 'MusicRecording';
  } else {
    $schemaType = 'WebPage';
  }
?>
<html itemscope itemtype="http://schema.org/<?php echo $schemaType; ?>"
      xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml"
      xml:lang="<?php echo $i18n->whichLang(); ?>" lang="<?php echo $i18n->whichLang(); ?>">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# tuneefy: http://ogp.me/ns/fb/tuneefy#">

<?php // Encoding // ?>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="content-language" content="<?php echo $i18n->whichLang(); ?>">
  
<?php // META standards // ?>
  <meta name="description" content="<?php $i18n->description; ?>" />
  <meta name="keywords" content="<?php $i18n->tags; ?>" />

<?php // Social:: Facebook  // ?>
<?php if ($action == 'search' && !isset($request)) { ?>

  <title><?php $i18n->home_title; ?> | <?php $i18n->general_title; ?></title>
  <meta property="og:title" content="<?php $i18n->home_title; ?> | <?php $i18n->general_title; ?>"/>
  <meta property="og:url" content="<?php echo _SITE_URL; ?>"/>
  <meta property="og:image" content="<?php echo _SITE_URL; ?>/img/social.png"/>
  <meta property="og:type" content="website"/>
  <meta property="og:description" content="<?php $i18n->description; ?>"/>

  <meta itemprop="name" content="<?php $i18n->general_title; ?>">
  <meta itemprop="description" content="<?php $i18n->description; ?>">
  <meta itemprop="image" content="<?php echo _SITE_URL; ?>/img/social.png">

<?php } else if ($action == 'search' && isset($request)) { ?>

  <title><?php $i18n->search_title(html_entity_decode($request)); ?> | <?php $i18n->general_title; ?></title>
  <meta property="og:title" content="<?php $i18n->search_title(html_entity_decode($request)); ?> | <?php $i18n->general_title; ?>"/>
  <meta property="og:url" content="<?php echo _SITE_URL."/search/".urlencode($request); ?>"/>
  <meta property="og:image" content="<?php echo _SITE_URL; ?>/img/social.png"/>
  <meta property="og:type" content="website"> 
  <meta property="og:description" content="<?php $i18n->description; ?>"/>

  <meta itemprop="name" content="<?php $i18n->general_title; ?>">
  <meta itemprop="description" content="<?php $i18n->description; ?>">
  <meta itemprop="image" content="<?php echo _SITE_URL; ?>/img/social.png">

<?php } else if ($action == 'track') { ?>

<?php 
    
  $link = _SITE_URL."/t/".$request; 
  $iframeCode = sprintf(_IFRAME, $link);
  
?>
  <title><?php $i18n->track_title(esc($name), esc($artist)) ?> | <?php $i18n->general_title; ?></title>
  <meta property="og:title" content="<?php echo esc($name); ?> | <?php echo esc($artist); ?>"/>
  <meta property="og:url" content="<?php echo $link; ?>"/>
  <meta property="og:image" content="<?php echo $image; ?>"/>
  <meta property="tuneefy:artist" content="<?php echo esc($artist); ?>"/>
  <meta property="tuneefy:album" content="<?php echo esc($album); ?>" />
  <meta property="og:description" content="<?php $i18n->track_description(esc($name),esc($artist)); ?>"/>
  <meta property="og:type" content="tuneefy:track"> 

  <meta itemprop="name" content="<?php echo esc($name); ?> | <?php echo esc($artist); ?>">
  <meta itemprop="description" content="<?php $i18n->track_description(esc($name),esc($artist)); ?>">
  <meta itemprop="image" content="<?php echo $image; ?>">

<?php } else if ($action == 'album') { ?>

<?php

  $link = _SITE_URL."/a/".$request;
  $iframeCode = sprintf(_IFRAME, $link);
  
?>
  <title><?php $i18n->album_title(esc($album),esc($artist)) ?> | <?php $i18n->general_title; ?></title>
  <meta property="og:title" content="<?php echo esc($album); ?> | <?php echo esc($artist); ?>"/>
  <meta property="og:url" content="<?php echo $link; ?>"/>
  <meta property="og:image" content="<?php echo $image; ?>"/>
  <meta property="tuneefy:artist" content="<?php echo esc($artist); ?>"/>
  <meta property="tuneefy:album" content="<?php echo esc($album); ?>" />
  <meta property="og:description" content="<?php $i18n->album_description(esc($album), esc($artist)); ?>"/>
  <meta property="og:type" content="tuneefy:track"> 
      
  <meta itemprop="name" content="<?php echo esc($album); ?> | <?php echo esc($artist); ?>">
  <meta itemprop="description" content="<?php $i18n->album_description(esc($album), esc($artist)); ?>">
  <meta itemprop="image" content="<?php echo $image; ?>">

<?php } else if ($action == 'trends') {  ?>

  <title><?php $i18n->stats_title; ?> | <?php $i18n->general_title; ?></title>
  <meta property="og:title" content="<?php $i18n->stats_title; ?> | <?php $i18n->general_title; ?>"/>
  <meta property="og:url" content="<?php echo _SITE_URL; ?>/trends"/>
  <meta property="og:image" content="<?php echo _SITE_URL; ?>/img/social.png"/>
  <meta property="og:type" content="website"/>
  
  <meta itemprop="name" content="<?php $i18n->stats_title; ?> | <?php $i18n->general_title; ?>">
  <meta itemprop="description" content="<?php $i18n->description; ?>">
  <meta itemprop="image" content="<?php echo _SITE_URL; ?>/img/social.png">

<?php } else if ($action == 'about') {  ?>

  <title><?php $i18n->about_title; ?> | <?php $i18n->general_title; ?></title>
  <meta property="og:title" content="<?php $i18n->about_title; ?> | <?php $i18n->general_title; ?>"/>
  <meta property="og:url" content="<?php echo _SITE_URL; ?>/about"/>
  <meta property="og:image" content="<?php echo _SITE_URL; ?>/img/social.png"/>
  <meta property="og:type" content="website"/>

  <meta itemprop="name" content="<?php $i18n->about_title; ?> | <?php $i18n->general_title; ?>">
  <meta itemprop="description" content="<?php $i18n->description; ?>">
  <meta itemprop="image" content="<?php echo _SITE_URL; ?>/img/social.png">

<?php } else if ($action == 'api_doc') {  ?>

  <title><?php $i18n->api_title; ?> | <?php $i18n->general_title; ?></title>
  <meta property="og:title" content="<?php $i18n->api_title; ?> | <?php $i18n->general_title; ?>"/>
  <meta property="og:url" content="<?php echo _API_URL; ?>"/>
  <meta property="og:image" content="<?php echo _SITE_URL; ?>/img/social.png"/>
  <meta property="og:type" content="website"/>

  <meta itemprop="name" content="<?php $i18n->api_title; ?> | <?php $i18n->general_title; ?>">
  <meta itemprop="description" content="<?php $i18n->description; ?>">
  <meta itemprop="image" content="<?php echo _SITE_URL; ?>/img/social.png">

<?php } else if ($action == 'admin') {  ?>

  <title><?php $i18n->general_title; ?></title>
  <meta property="og:title" content="<?php $i18n->general_title; ?>"/>
  <meta property="og:url" content="<?php echo _SITE_URL; ?>"/>
  <meta property="og:type" content="website"/>
  
<?php } else {  ?>

  <title><?php $i18n->general_title; ?></title>
  <meta property="og:title" content="<?php $i18n->general_title; ?>"/>
  <meta property="og:url" content="<?php echo _SITE_URL; ?>"/>
  <meta property="og:image" content="<?php echo _SITE_URL; ?>/img/social.png"/>
  <meta property="og:type" content="website"/>
  
  <meta itemprop="name" content="<?php $i18n->general_title; ?>">
  <meta itemprop="description" content="<?php $i18n->description; ?>">
  <meta itemprop="image" content="<?php echo _SITE_URL; ?>/img/social.png">

<?php } ?>

  <meta property="fb:app_id" content="<?php echo _FB_APP_ID; ?>"> 
  <meta property="fb:page_id" content="<?php echo _FB_PAGE_ID; ?>">
  <meta property="og:site_name" content="<?php $i18n->general_title; ?>"/>
  <meta property="fb:admins" content="<?php echo _FB_ADMIN; ?>"/>
        
<?php // Social:: Google  // ?>
  <link rel="publisher" href="<?php echo _GPLUS_PUBLISHER_ID; ?>">
  
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
  
<?php // jQuery // ?>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>

<?php // FAVICON ?>
  <link rel="icon" type="image/png" href="<?php echo _SITE_URL; ?>/img/favicon.png" />
<!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico" /><![endif]-->

<?php // Style // ?>
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo _SITE_URL; ?>/css/reset.css" />
  <meta content="yes" name="apple-mobile-web-app-capable" />
  <link href="<?php echo _SITE_URL; ?>/img/social.png" rel="apple-touch-icon" />
<?php if ($mobile) { ?>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo _SITE_URL; ?>/css/mobile.css" />
  <meta content="width=device-width, user-scalable=no" name="viewport" />
<?php } else { ?>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo _SITE_URL; ?>/css/general.css" />
<?php if (($action == 'track' || $action == 'album') && $iframeMode) { // for iframeMode ?>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo _SITE_URL; ?>/css/embed.css" />
<?php } else { ?>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo _SITE_URL; ?>/css/style.css" />
<?php } ?>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo _SITE_URL; ?>/css/iphone-style.css" />
<!--[if lte IE 9]>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo _SITE_URL; ?>/css/ie.css" />
<![endif]-->
<?php } ?>

<?php /* *************************** */
      // ******  JS FOR PAGES ****** //
      /* *************************** */ ?>
<?php $jsVersion = 5; ?>
<?php if ($action == 'search' || $action == "playlists" ) { ?>
<?php 
  $defaultPlatforms ="";
  $allPlatforms ="";
  $jsAllPlatforms = "";
  
  $platforms = API::getPlatforms();
  
  while (list($pId, $pObject) = each($platforms))
  {
    $defaultPlatforms .= ($pObject->isDefault() && $pObject->isActiveForSearch())?$pObject->getId().",":"";
    $allPlatforms .= $pObject->isActiveForSearch()?$pObject->getId().",":"";
    $jsAllPlatforms .= $pObject->isActiveForSearch()?'_p['.$pObject->getId().'] = "'.$pObject->getName().'";':"";
  }
  reset($platforms);
?>
  <script type="text/javascript">
    _p = [];<?php echo $jsAllPlatforms; ?>
    _all_platforms = <?php echo '"'.substr($allPlatforms,0, -1).'"'; ?>;
    _default_platforms = <?php if (!$mobile) { echo '"'.substr($defaultPlatforms,0, -1).'"';} else { echo '_all_platforms'; } ?>;
    _table_link_prefix = <?php echo '"'._TABLE_LINK_PREFIX.'"'; ?>;
  </script>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/lang/lang.js.php?v=<?=$jsVersion?>&l=<?=$i18n->whichLang()?>"></script>
<?php // Classes - OO Javascript // ?>
<?php if ($dev) { ?>
  <script type="text/javascript">
      if (!window.console) console = {log: function() {}};
  </script>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/dev/ControlFreak.class.js?v=<?=$jsVersion?>"></script>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/dev/Model/Tuneefy.class.js?v=<?=$jsVersion?>"></script>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/dev/Model/Tuneefy.item.class.js?v=<?=$jsVersion?>"></script>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/dev/Model/Tuneefy.links.class.js?v=<?=$jsVersion?>"></script>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/dev/UI/iphone-style.js?v=<?=$jsVersion?>"></script>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/dev/UI/SearchUI.class.js?v=<?=$jsVersion?>"></script>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/dev/UI/PlaylistsUI.class.js?v=<?=$jsVersion?>"></script>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/dev/UI/AlertUI.class.js?v=<?=$jsVersion?>"></script>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/dev/UI/ResultRenderUI.class.js?v=<?=$jsVersion?>"></script>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/dev/UI/SliderUI.class.js?v=<?=$jsVersion?>"></script>
<?php } else { ?>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/min/tuneefy.min.js?v=<?=$jsVersion?>"></script>
<?php } } else if ($action == 'track') { ?>
  <script type="text/javascript">
    function postToFeed() {
      var obj = {
        method: 'feed',
        link: '<?php echo $link; ?>',
        display: 'dialog',
        picture: '<?php echo ($image == "null" || $image == "")?_SITE_URL."/img/nothumb_track.png":$image; ?>',
        name: "<?php echo esc($name); ?> | <?php echo esc($artist); ?>",
        description: "<?php $i18n->track_description(esc($name),esc($artist)); ?>",
        actions: [{ name: '<?php $i18n->track_facebook_action; ?>', link: '<?php echo _SITE_URL; ?>' }]
      };
      function callback(response) {}
      FB.ui(obj, callback);
    };
    
    function toggleEmbed() {
      $('#embedHolder').toggle();
      $('#embed').toggleClass('open');
    };
    
    function newTweet(){
      var width  = 575,height = 400,
          left   = ($(window).width()  - width)  / 2,
          top    = ($(window).height() - height) / 2,
          url    = "https://twitter.com/home?status=<?php $i18n->track_twitter_status(esc(urlencode($name)), esc(urlencode($artist)), $link); ?>",
          opts   = 'status=1' +
                   ',width='  + width  +
                   ',height=' + height +
                   ',top='    + top    +
                   ',left='   + left;
      
      window.open(url, 'twitter', opts);
    };
    
    window.fbAsyncInit = function() {
      FB.init({ appId:'<?php echo _FB_APP_ID; ?>', cookie:true, status:true, xfbml:true });
    };
  </script>
<?php } else if ($action == 'album') { ?>
  <script type="text/javascript">
    function postToFeed() {
      var obj = {
        method: 'feed',
        link: '<?php echo $link; ?>',
        display: 'dialog',
        picture: '<?php echo ($image == "null" || $image == "")?_SITE_URL."/img/nothumb_album.png":$image; ?>',
        name: "<?php echo esc($album); ?> | <?php echo esc($artist); ?>",
        description: "<?php $i18n->album_description(esc($album),esc($artist)); ?>",
        actions: [{ name: '<?php $i18n->album_facebook_action; ?>', link: '<?php echo _SITE_URL; ?>' }]
      };
      function callback(response) {}
      FB.ui(obj, callback);
    };
    
    function toggleEmbed() {
      $('#embedHolder').toggle();
      $('#embed').toggleClass('open');
    };
    
    function newTweet(){
      var width  = 575,height = 400,
          left   = ($(window).width()  - width)  / 2,
          top    = ($(window).height() - height) / 2,
          url    = "https://twitter.com/home?status=<?php $i18n->album_twitter_status(esc(urlencode($album)), esc(urlencode($artist)), $link); ?>",
          opts   = 'status=1' +
                   ',width='  + width  +
                   ',height=' + height +
                   ',top='    + top    +
                   ',left='   + left;
      
      window.open(url, 'twitter', opts);
    };
    
    window.fbAsyncInit = function() {
      FB.init({ appId:'<?php echo _FB_APP_ID; ?>', cookie:true, status:true, xfbml:true });
    };
  </script>
<?php } else if ($action == 'trends') { ?>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/min/raphael.min.js"></script>
<?php   if ($dev) { ?>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/dev/Charts/pie.js"></script>
<?php   } else { ?>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/min/pie.min.js"></script>
<?php   } ?>
<?php } ?>

<?php if ($action == 'playlists') { ?>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/min/md5-min.js"></script>
<?php } ?>

<?php /* *************************** */ 
      // Javascript main entry point // 
      /* *************************** */ ?>
  <script type="text/javascript">
  $(document).ready(function() {
  
<?php // LANGS // ?>
<?php if (!(($action == 'track' || $action == 'album') && $iframeMode)) { // end display:none for iframeMode ?>
    $('#lang span').click(function(e){
      var value = "tuneefyLocale=" + $(e.target).attr("lang") + "; ";
      value += "expires=Sat, 01 Feb 2042 01:20:42 GMT; path=/; domain= .tuneefy.com;"; 
      document.cookie= value;
      location.reload();
    });  
<?php } // end display:none for iframeMode ?>  
<?php if ($action == 'search') { ?>
    var controller = new ControlFreak();
    
<?php if (!$mobile) { ?>
    var sliderView = new SliderUI(4000);
    window.setTimeout('$("#social").fadeIn(1000)',2000);
<?php } ?>

    var model = new Tuneefy(controller);
    var searchView = new SearchUI(controller);
    var alertView = new AlertUI(controller, 1);
    var resultsView = new ResultRenderUI(model, controller, 10, 10, false);
    
    window.onpopstate = function(event) {
      $(controller).trigger("tuneefy.search.popstate", event);
    }
    
<?php if (isset($request)) {?>
    $(controller).trigger("tuneefy.search.trigger");
<?php } ?>
<?php } else if ($action == 'playlists' ) { ?>
    var controller = new ControlFreak();
    
<?php if (!$mobile) { ?>
    window.setTimeout('$("#social").fadeIn(1000)',2000);
<?php } ?>

    var model = new Tuneefy(controller);
    var playlistsView = new PlaylistsUI(controller);
    var alertView = new AlertUI(controller, 1);
    var resultsView = new ResultRenderUI(model, controller, 10, 10, false);
    
<?php } else if ($action == 'track' || $action == 'album') { ?>
<?php if (!$mobile && !$iframeMode) { ?>
    window.setTimeout('$("#social").fadeIn(1000)',2000);
<?php } ?>
    $("#shareLink, #embedContent").click(function(e){
      $(e.target).focus();
      $(e.target).select();
    });
<?php } else if ($action == 'trends') { ?>  
    var values = [], labels = [];
    $("tr").each(function () {
        values.push(parseInt($("td", this).text(), 10));
        labels.push(new Array($("th span.id", this).text(), $("th span.name", this).text(), $("th span.color", this).text()));
    });
    $("table#pieData").hide();
    Raphael("pieChart", 650, 500).pieChart(325, 250, 200, values, labels, "#2d2d2d");
<?php } else if ($action == 'about') { ?>
<?php if (!$mobile) { ?>
    window.setTimeout('$("#social").fadeIn(1000)',2000);
<?php } ?>

    var u = $("ul.platformsPatterns li.platform");
    
    $("form.contactForm #send").click(function(){
  
      var e = $("form.contactForm #email");
      var m = $("form.contactForm #message");
      
      m.removeClass("error");
      e.removeClass("error");
      
      if (e.val() == "") {
        e.addClass("error");
        return false;
      }
      if (m.val() == "") {
        m.addClass("error");
        return false;
      }
      
      $("form.contactForm").hide();
      $(".waitingMail").show();
    
      $.post("/include/share/mail.php", {mail:e.val(), message: m.val()}, function(data){
        
        $(".waitingMail").hide();
        
        if (data == "1") {
          $(".successMail").show();
        } else {
          $(".errorMail").show();
        }
      });
    
    });
    
    u.click(function(e){
      if (!$(e.target).hasClass("platform")) return; 
      u.removeClass("active");
      $(e.target).addClass("active");
      u.find("ul").hide();
      $(e.target).find("ul").show();
    });

<?php } else { ?>
<?php if (!$mobile) { ?>
    window.setTimeout('$("#social").fadeIn(1000)',2000);
<?php } ?>
<?php } ?>
  });
  </script>

</head>
<body <?php if ($mobile) echo 'onload="window.scrollTo(0, 1)"'; ?> class="Bcolor"> 
<div class="hideAll"></div>
<div class="ribbon">
  <a href="https://github.com/tchapi/tuneefy" rel="me"><?php $i18n->github; ?></a>
</div>
<?php if (($action == 'track' || $action == 'album') && $iframeMode) { ?>
<div id="wrapper" class="boxed boxS">
<?php } else { // end display:none for iframeMode ?>
<div id="wrapper">
  <div id="header-wrapper" class="full Fcolor txtColor">
    <div class="wrap clear" id="header">
  
        <div id="lang">
        <span <?php if ($i18n->whichLang() == "en") {?>class="activeLang"<?php } ?> lang="en">EN</span>
        <span <?php if ($i18n->whichLang() == "fr") {?>class="activeLang"<?php } ?> lang="fr">FR</span>
        </div>
<?php if (!$mobile) { ?>
        <div id="social" style="display: none;">
<?php //////// TWITTER //////// ?>
      <div id="share_twitter">
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo _SITE_URL; ?>" data-text="<?php $i18n->description; ?>" data-lang="<?php echo $i18n->whichLang() ?>" data-hashtags="tuneefy">Tweet</a>
  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
      </div>
<?php //////// END TWITTER //////// ?>
          
<?php //////// GOOGLE //////// ?>
      <div id="share_plusone">
  <script type="text/javascript">
  (function() {
  var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
  po.src = 'https://apis.google.com/js/plusone.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
  </script>
        <g:plusone size="medium" href="<?php echo _SITE_URL; ?>"></g:plusone>
      </div>
<?php //////// END GOOGLE //////// ?>

<?php //////// FACEBOOK //////// ?>
      <div id="share_facebook">
  <script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>   
      <div class="fb-like" data-href="<?php echo _SITE_URL; ?>" data-send="false" data-layout="button_count" data-width="90" data-show-faces="false" data-colorscheme="light" data-font="arial"></div>
      </div>
<?php //////// END FACEBOOK //////// ?>
      </div>
<?php } ?>

<?php if (!$mobile && (!$iframeMode || ($action != 'track' && $action != 'album')) && _USERVOICE_ENABLED) { ?>      
<script type="text/javascript">
var uvOptions = {};
(function() {
  var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
  uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/381pBuGNOqnoSuYjIMIBg.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
})();
</script>
<?php } ?>

      <ul id="navigation">
        <li><a <?php if ($action != 'about' && $action != 'trends') echo 'class="current txtS3"'; ?> title="<?php $i18n->home_tip; ?>" href="<?php echo _SITE_URL; ?>"><img class="homeImg" src="<?php echo _SITE_URL; ?>/img/home_pic.png" width="12px" height="14px" /><?php $i18n->home_title; ?></a></li><?php if (!_DESACTIVATE_TRENDS && $mobile === false) {?><li><a <?php if ($action == 'trends') echo 'class="current txtS3"'; ?> title="<?php $i18n->stats_tip; ?>" href="<?php echo _SITE_URL; ?>/trends"><?php $i18n->stats_title; ?></a></li><?php } ?><li><a <?php if ($action == 'about') echo 'class="current txtS3"'; ?> title="<?php $i18n->about_tip; ?>" href="<?php echo _SITE_URL; ?>/about"><?php $i18n->about_title; ?></a></li>
      </ul>
    </div>
  </div>
<?php } // end display:none for iframeMode ?>
  <div id="content-wrapper" class="full">
    <div class="wrap clear bdBot" id="content">
<?php if (!(($action == 'track' || $action == 'album') && $iframeMode)) { // end display:none for iframeMode ?>
      <div id="tagline" class="bdBot">
        <a href="<?php echo _SITE_URL; ?>"><h1 class="logo color"><img src="<?php echo _SITE_URL; ?>/img/logo.png" width="267" height="133" alt="<?php $i18n->general_title; ?>"/></h1> </a>
        <p class="tagline txtS"><?php $i18n->tagline; ?></p>
      </div>
<?php } else { // end display:none for iframeMode ?>
      <div id="tagline" class="bdBot">
        <a href="<?php echo _SITE_URL; ?>" target="_blank"><h1 class="logo color"><img src="<?php echo _SITE_URL; ?>/img/logo.png" width="100" alt="<?php $i18n->general_title; ?>"/></h1></a>
        <p class="tagline txtS"><?php $i18n->tagline; ?></p>
      </div>
<?php } ?>
