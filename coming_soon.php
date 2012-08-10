<?php

  /*  COMING SOON
   *  Utility functions to get the mail of the client
   */
   
  /**
  Validate an email address.
  Provide email address (raw input)
  Returns true if the email address has the email 
  address format and the domain exists.
  Taken from : http://www.linuxjournal.com/article/9585
  */
  function validEmail($email)
  {
     $isValid = true;
     $atIndex = strrpos($email, "@");
     if (is_bool($atIndex) && !$atIndex)
     {
        $isValid = false;
     }
     else
     {
        $domain = substr($email, $atIndex+1);
        $local = substr($email, 0, $atIndex);
        $localLen = strlen($local);
        $domainLen = strlen($domain);
        if ($localLen < 1 || $localLen > 64)
        {
           // local part length exceeded
           $isValid = false;
        }
        else if ($domainLen < 1 || $domainLen > 255)
        {
           // domain part length exceeded
           $isValid = false;
        }
        else if ($local[0] == '.' || $local[$localLen-1] == '.')
        {
           // local part starts or ends with '.'
           $isValid = false;
        }
        else if (preg_match('/\\.\\./', $local))
        {
           // local part has two consecutive dots
           $isValid = false;
        }
        else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
        {
           // character not valid in domain part
           $isValid = false;
        }
        else if (preg_match('/\\.\\./', $domain))
        {
           // domain part has two consecutive dots
           $isValid = false;
        }
        else if
  (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                   str_replace("\\\\","",$local)))
        {
           // character not valid in local part unless 
           // local part is quoted
           if (!preg_match('/^"(\\\\"|[^"])+"$/',
               str_replace("\\\\","",$local)))
           {
              $isValid = false;
           }
        }
        if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
        {
           // domain not found in DNS
           $isValid = false;
        }
     }
     return $isValid;
  }
  
  //  
  // addMailEntry()
  // adds a mail address into the table
  //
  function addMailEntry($mail) {

    if ( !validEmail($mail) ) return false;
    
    $query  = sprintf("INSERT INTO `coming_soon_mails` (mail, date) VALUES ( '%s', NOW())", 
                        mysql_real_escape_string($mail)
                      );
                      
    // Executes the query
    $exe = mysql_query($query);
    
    // Returns true if the query was well executed and returned a single line
    if ($exe && mysql_affected_rows() == 1 ) {
      return true;
    } else return false;
  
  }

  require('config.php');
  
  require(_PATH.'include/langs/i18nHelper.php');
  
  $failSilently = true;
  require(_PATH.'include/database/DBConnection.class.php');
  
  if (isset($_POST['mail']) && $_POST['mail'] != NULL) {
    $state = addMailEntry(trim($_POST['mail']));
    echo ($state==true)?"1":"0";
    return true;
  }
  
?><!DOCTYPE html>
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

<?php // Social:: Facebook  // ?>
  <title><?php $i18n->coming_soon_title; ?></title>
  <meta property="og:title" content="<?php $i18n->coming_soon_title; ?>"/>
  <meta property="og:url" content="<?php echo _SITE_URL; ?>/teaser"/>
  <meta property="og:image" content="<?php echo _SITE_URL; ?>/img/social.png"/>
  <meta property="og:type" content="website"/>
  <meta property="fb:app_id" content="<?php echo _FB_APP_ID; ?>"> 
  <meta property="fb:page_id" content="<?php echo _FB_PAGE_ID; ?>">
  <meta property="og:site_name" content="<?php $i18n->general_title; ?>"/>
  <meta property="fb:admins" content="<?php echo _FB_ADMIN; ?>"/>
  <meta property="og:description" content="<?php $i18n->description; ?>"/>
  
<?php // Social:: Google  // ?>
  <meta itemprop="name" content="<?php $i18n->general_title; ?>">
  <meta itemprop="description" content="<?php $i18n->description; ?>">
  <meta itemprop="image" content="<?php echo _SITE_URL; ?>/img/social.png">

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
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo _SITE_URL; ?>/img/favicon.ico" />

<?php // Style // ?>
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo _SITE_URL; ?>/css/reset.css" />
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo _SITE_URL; ?>/css/coming.css" />
<!--[if lte IE 9]>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo _SITE_URL; ?>/css/ie.css" />
<![endif]-->
  
  <script type="text/javascript">
$(document).ready(function() {

var e = $("#mail_signin_notify"); var i = $("#mail_signin"); var h = $(".hideAll");
var w = $("#waiting");

i.submit(function() {
  w.show();
  var e_v = e.val();
  $.post("coming_soon.php", {mail : e_v}, function(data) {
    h.fadeOut(); $('.alert').hide(); w.fadeOut();
    if (data == "1"){
      $("#mailForm").hide();
      $('#success').show();
      $('#success_message').fadeIn();
      h.remove();
    } else {
      $('#error_message').fadeIn();
    }
    i.removeClass("focused");
  });
  return false;
});

e.focus(function(ev){
  i.addClass("focused"); h.fadeTo(500, 0.5);
  if (e.val() == "<?php $i18n->coming_soon_label; ?>") { e.val("");}
});

e.blur(function(ev){
  i.removeClass("focused"); h.fadeOut();    
  if (e.val() == "") { e.val("<?php $i18n->coming_soon_label; ?>");}
});

$("#info_message").fadeIn(2000);
window.setTimeout('$("#social").fadeIn(1000)',1000);
    
});
  </script>
    
</head>
<body class="Bcolor">
<div class="hideAll"></div>
<div id="wrapper">

  <div id="content-wrapper" class="full">
    <div class="wrap clear bdBot" id="content">
    
      <div id="tagline" class="bdBot">
        <h1 class="logo color"><img src="<?php echo _SITE_URL; ?>/img/logo.png" width="267" height="133" alt="<?php $i18n->general_title; ?>"/></h1> 
        <p class="tagline txtS"><?php $i18n->coming_soon_tagline; ?></p>
      </div>

      <div id="mailForm" class="bdTop">
        <form action="#" method="POST" id="mail_signin">
          <input type="text" value="<?php $i18n->coming_soon_label; ?>" id="mail_signin_notify" name="mail_signin_notify" class="required email boxS5"/>
          <input type="submit" value="<?php $i18n->coming_soon_submit; ?>" id="mail_signin_notify_submit" name="mail_signin_notify_submit" class="boxS2 txtColor"/>
        </form>
          
        <div id="waiting" style="display: none;"><img src="<?php echo _SITE_URL; ?>/img/ajax-loader.gif" /></div>
          
        <div id="info_message" class="alert txtS">
          <div class="triangle"></div>
          <?php $i18n->coming_soon_info; ?></div>
        
        <div id="error_message" style="display: none;" class="alert txtS">
          <img src="<?php echo _SITE_URL; ?>/img/error.png" width="40px" height="40px" />
          <div class="triangle"></div>
          <div class="message"><?php echo $i18n->coming_soon_oops; ?></div>
        </div>
        
      </div>
      
      <div id="success" class="bdTop" style="display: none;">
        <div id="success_message" class="alert txtS">
          <img src="<?php echo _SITE_URL; ?>/img/success.png" width="74px" height="74px" />
          <div class="message"><?php $i18n->coming_soon_thanks; ?></div>
        </div>
      </div>
      
    </div>
  </div>
  
  <div id="footer-wrapper" class="full">
  <div id="footer" class="wrap bdTop txtS">
    
   <div class="endorsement"><?php $i18n->coming_soon_disclaimer; ?></div>
    <div class="copycon">
      <?php echo $i18n->copyright; ?> | <a href="https://twitter.com/tuneefy" target="_new" rel="nofollow" class="twitterLink"><?php $i18n->coming_soon_twitter; ?></a><?php /* | <a href="https://twitter.com/tuneefy" class="twitter-follow-button" data-width="130px" data-show-count="false" data-lang="en">Follow @tuneefy</a> | <a href="http://tuneefy.tumblr.com"><?php $i18n->coming_soon_blog; ?></a> */ ?>
    </div>
    
    <div id="social" style="display:none;">
      
<?php //////// TWITTER //////// ?>
      <div id="share_twitter">
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo _SITE_URL; ?>/teaser" data-text="<?php $i18n->description; ?>" data-lang="en" data-hashtags="tuneefy">Tweet</a>
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
    
    </div>
  </div>

</div>
</body>
</html>