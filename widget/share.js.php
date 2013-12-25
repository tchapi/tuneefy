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
  
    if (uri.indexOf(".deezer.") && el("player_track_title") != null) {
    
      artist = el("player_track_artist").innerHTML;
      song = el("player_track_title").innerHTML;

    } /*else if (uri.indexOf(".jiwa.") && el("player") != null) {
    
      artist = el("player").childNodes[5].childNodes[1].innerHTML.replace(" /","");
      song = el("player").childNodes[5].childNodes[3].innerHTML;

    }*/ else if (uri.indexOf(".grooveshark.") && el("now-playing-metadata") != null) {
    
      artist = el("now-playing-metadata").childNodes[4].innerHTML;
      song = el("now-playing-metadata").firstChild.innerHTML;

    } else if (uri.indexOf(".radionomy.") && el("track-name") != null) {
    
      artist = el("artist-name").innerHTML;
      song = el("track-name").innerHTML;

    } else if (uri.indexOf(".stereomood.") && el("info_track_title") != null) {
    
      artist = el("info_track_artist").innerHTML;
      song = el("info_track_title").innerHTML;

    } else if (uri.indexOf(".musicmaze.") && el("song-title") != null) {
    
      artist = el("artist-name").firstChild.innerHTML;
      song = el("song-title").firstChild.innerHTML;

    } /* else if (uri.indexOf(".youtube.") && el("watch-description-extra-info") != null) {
    
      artist = el("watch-description-extra-info").childNodes[5].childNodes[3].childNodes[0].nodeValue.split('"')[1];
      song = el("watch-description-extra-info").childNodes[3].childNodes[1].childNodes[3].childNodes[1].innerHTML;

    }*/ else if (uri.indexOf(".myspace.com/music/player") && el("mainContent") != null) {
    
      artist = el("mainContent").childNodes[3].childNodes[11].childNodes[1].childNodes[5].firstChild.innerHTML;
      song = el("mainContent").childNodes[3].childNodes[11].childNodes[1].childNodes[3].firstChild.innerHTML;

    } else if (uri.indexOf(".myspace.") && document.getElementsByTagName("h1")[1] != null) {
    
      artist = document.getElementsByTagName("h1")[1].firstChild.innerHTML;
      song = document.getElementsByTagName("h6")[0].firstChild.firstChild.innerHTML;

    } else if (uri.indexOf("player.qobuz.") && el("now-playing") != null) {
    
      uri = encodeURIComponent("http://player.qobuz.com" + document.getElementById("now-playing").childNodes[4].childNodes[1].childNodes[3].getAttribute("href"));

    } else if (uri.indexOf("music.xbox.") && el("player") != null){

      // Jquery is here ! \o/
      song = $("#player").find(".playerNowPlaying .playerNowPlayingMetadata .primaryMetadata a").html();
      artist = $("#player").find(".playerNowPlaying .playerNowPlayingMetadata .secondaryMetadata a:first-child").html();
      //album = $("#player").find(".playerNowPlaying .playerNowPlayingMetadata .secondaryMetadata a:last-child").html();

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
