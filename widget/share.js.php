<?php

  require('../config.php');
  header("Content-Type: application/javascript, charset=UTF-8");
    
  if (isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], _SITE_URL) !== false && strpos($_SERVER["HTTP_REFERER"], "facebook.") !== false) {
    // We are on the site, let's alert that we cannot do this
    echo "alert('You cannot do this on this page, bro, come on.');";
    return;
  }
  
?>/* Tuneefy (c)2011-2012 */
function addCSS(url){
  var headID = document.getElementsByTagName("head")[0];
  var cssNode = document.createElement('link');
  cssNode.type = 'text/css';
  cssNode.rel = 'stylesheet';
  cssNode.href = url;
  cssNode.media = 'screen';
  headID.appendChild(cssNode);
}

function el(id){return document.getElementById(id);}

function elcl(className, parent) {
  parent || (parent = document);
  var descendants= parent.getElementsByTagName('*'), i=-1, e, result=[];
  while (e=descendants[++i]) {
    ((' '+(e['class']||e.className)+' ').indexOf(' '+className+' ') > -1) && result.push(e);
  }
  return result;
}

(function(){

<?php  // We add the CSS ?>
  addCSS("<?php echo _SITE_URL; ?>/widget/mini.css");
  
<?php  // Let's display the box, with the loader ?>
  var alreadyThere = el("tuneefy_overlay");
  
  if (alreadyThere) {
    divToDisplay = alreadyThere;
  } else {
    var divToDisplay = document.createElement("div");
    divToDisplay.id = "tuneefy_overlay";
  }
  
  var closeButton = "<div class=\"closeButton\" onClick=\"document.body.removeChild(document.getElementById('tuneefy_overlay'));\"></div>";
  
  var loaderImage = "<img src=\"<?php echo _SITE_URL; ?>/img/ajax-loader-widget.gif\" class=\"middleBox\"/>";
  
  divToDisplay.innerHTML = closeButton + loaderImage;
  
<?php  // Animation ?>

  divToDisplay.style.opacity =1;
  
<?php  // We compute what we need to compute ?>
  var uri = encodeURIComponent(decodeURIComponent(document.location.href));
  var song = null, artist = null, query = "";
  
  try {
  
    if (uri.indexOf(".deezer.") != -1 && el("player_track_title") != null) {
    
      artist = el("player_track_artist").innerHTML;
      song = el("player_track_title").innerHTML;

    } else if (uri.indexOf(".deezer.") != -1 && elcl("player-track-title") != null) {
    
      artist = elcl('player-track-artist')[0].childNodes[1].innerHTML;
      song = elcl('player-track-title')[0].firstChild.innerHTML;

    }/*else if (uri.indexOf(".jiwa.") != -1 && el("player") != null) {
    
      artist = el("player").childNodes[5].childNodes[1].innerHTML.replace(" /","");
      song = el("player").childNodes[5].childNodes[3].innerHTML;

    }*/ else if (uri.indexOf(".grooveshark.") != -1 && el("now-playing-metadata") != null) {
    
      artist = el("now-playing-metadata").childNodes[4].innerHTML;
      song = el("now-playing-metadata").firstChild.innerHTML;

    } else if (uri.indexOf(".radionomy.") != -1 && el("track-name") != null) {
    
      artist = el("artist-name").innerHTML;
      song = el("track-name").innerHTML;

    } else if (uri.indexOf(".stereomood.") != -1 && el("info_track_title") != null) {
    
      artist = el("info_track_artist").innerHTML;
      song = el("info_track_title").innerHTML;

    } else if (uri.indexOf(".musicmaze.") != -1 && el("song-title") != null) {
    
      artist = el("artist-name").firstChild.innerHTML;
      song = el("song-title").firstChild.innerHTML;

    } /* else if (uri.indexOf(".youtube.") != -1 && el("watch-description-extra-info") != null) {
    
      artist = el("watch-description-extra-info").childNodes[5].childNodes[3].childNodes[0].nodeValue.split('"')[1];
      song = el("watch-description-extra-info").childNodes[3].childNodes[1].childNodes[3].childNodes[1].innerHTML;

    }*/ else if (uri.indexOf(".myspace.com/music/player") != -1 && el("mainContent") != null) {
    
      artist = el("mainContent").childNodes[3].childNodes[11].childNodes[1].childNodes[5].firstChild.innerHTML;
      song = el("mainContent").childNodes[3].childNodes[11].childNodes[1].childNodes[3].firstChild.innerHTML;

    } else if (uri.indexOf(".myspace.") != -1 && document.getElementsByTagName("h1")[1] != null) {
    
      artist = document.getElementsByTagName("h1")[1].firstChild.innerHTML;
      song = document.getElementsByTagName("h6")[0].firstChild.firstChild.innerHTML;

    } else if (uri.indexOf("player.qobuz.") != -1 && el("now-playing") != null) {
    
      uri = encodeURIComponent("http://player.qobuz.com" + el("now-playing").childNodes[4].childNodes[1].childNodes[3].getAttribute("href"));

    } else if (uri.indexOf("music.xbox.") != -1 && el("player") != null){

      // Jquery is here ! \o/
      song = $("#player").find(".playerNowPlaying .playerNowPlayingMetadata .primaryMetadata a").html();
      artist = $("#player").find(".playerNowPlaying .playerNowPlayingMetadata .secondaryMetadata a:first-child").html();
      //album = $("#player").find(".playerNowPlaying .playerNowPlayingMetadata .secondaryMetadata a:last-child").html();

    } else if (uri.indexOf("radiooooo.") != -1 && elcl("songinfo--box")[0] != null){

      artist = elcl("song__artist")[0].innerHTML;
      song = elcl("song__title")[0].innerHTML;

    }
    
  } catch(e) { artist = null; song = null;}
  
  if (artist != null && typeof(artist) != 'undefined') {query += artist + '+';}
  if (song != null && typeof(song) != 'undefined') {query += song;}
  if (query == "") {
    query = uri;
  } else {
    query = encodeURIComponent(query);
  }
  
<?php  // Then we display the result ?>
  var iframe = "<iframe border=\"none\" allowtransparency=\"true\" class=\"tuneefyResults\" scrolling=\"no\" src=\"<?php echo _SITE_URL; ?>/?q=" + query + "&widget=42\"></iframe>";
  
  divToDisplay.innerHTML = closeButton + iframe;
  
<?php  // We add the DOM element ?>
  document.body.appendChild(divToDisplay);
})()
