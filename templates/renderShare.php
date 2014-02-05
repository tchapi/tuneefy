<?php

  // We change some DOM if we are in iframe mode
  if (!$iframeMode) {
  
    // Normal display
    $fullButtons = "_full";
    $target = "_top";
    
  } else {
  
    // iFrame mode
    $fullButtons = "";
    $target = "_blank";
    
  }
  // ------------------
  
  $linksForDisplay = $item['links'];

  // String for results
  $results = "";

  if ($linksForDisplay != null) {
  
    // Getting all the platforms links
    while (list($key, $val) = each($linksForDisplay))
    {
      $current = API::getPlatform($key);
      
      if ( $current != false && isset($linksForDisplay[$key]) && $linksForDisplay[$key] != "" && $linksForDisplay[$key] != "null" ) {
      
        if ($action == 'track') {
          $alt_title = str_replace('#p#',$current->getName(),str_replace('#name#', htmlentities($name), $i18n->get('listen_to')));
        } else if ($action == 'album') {
          $alt_title = str_replace('#p#',$current->getName(),str_replace('#name#', htmlentities($album), $i18n->get('listen_to')));
        }
        
        $results .= "<a target=\"".$target."\" class=\"btns".$fullButtons." btn".$fullButtons."_".$current->getId()."\" href=\"/include/share/listen.php?t=".urlencode($linksForDisplay[$key])."&p=".$current->getId()."&i=".$request."\" title=\"".$alt_title."\" >";
        $results .= "</a>";
        
      }
    }
    
  }
  
?>
<?php if (!$iframeMode) { // end display:none for iframeMode ?>
<div id="shareTitle" class="bdTop color">
  <p><?php echo $i18n->get($action.'_intro'); ?></p>
</div>
<div id="shareBox" class="boxed boxS txtS">
<?php } else { // end display:none for iframeMode ?>
<div id="shareBox" class="bdTop">
<?php } ?>
  <div id="trackInfo" class="bdBot">
<?php if ($iframeMode) { //  iframeMode ?><a href="<?php echo $link; ?>" target="_blank" title="<?php echo $i18n->get($action.'_seeOnTuneefy'); ?>"><?php } ?>
    <div class="cover">
      <img src="<?php echo ($image == "null" || $image == "")?_SITE_URL."/img/nothumb_track.png":$image; ?>" width="82" height="82" />
      <span class="coverlay"></span>
<?php if ($iframeMode) { //  iframeMode ?></a><?php } ?>
    </div>
    <div class="info">
    <div class="infoWrapper">
      <div class="infoContent">
<?php if ($iframeMode) { //  iframeMode ?><a href="<?php echo $link; ?>" target="_blank" title="<?php echo $i18n->get($action.'_seeOnTuneefy'); ?>" ><?php } ?>
<?php if ($action == 'track') { ?>
        <div class="title"><?php echo ellipsis($name,40); ?>
<?php } else if ($action == 'album') { ?>
        <div class="title"><?php echo ellipsis($album,40); ?>
<?php } ?>
<?php if ($iframeMode) { //  iframeMode ?><img class="newWindow" src="<?php echo _SITE_URL; ?>/img/new_window.png" width="14" /></div></a><?php } else { ?></div><?php } ?>
        <div class="artist"><?php echo ellipsis($artist, 40); ?></div>
<?php if ($action == 'track') { ?>
        <div class="album"><?php echo ($album == "null")?$i18n->various_albums:ellipsis($album,40); ?></div>
<?php } ?>
<?php if ($iframeMode) { ?><div id="platforms"><?php echo $results; ?></div><?php } ?>
      </div>
    </div>
    </div>
  </div>
<?php if (!$iframeMode) { // end display:none for iframeMode ?>
  <div id="platforms" class="bdBot bdTop2">
    <div class="listenTitle"><?php echo $i18n->get($action.'_listen_to'); ?></div>
    <?php echo $results; ?>
  </div>
  
  <div id="share" class="bdTop2">
    <div class="shareTitle"><?php echo $i18n->get($action.'_share'); ?></div>
    
    <div id="linkHolder" class="boxSinv">
      <input type="text" readonly="readonly" value="<?php echo $link; ?>" id="shareLink" class="boxS5"/>
    </div>
  
    <div id="socialHolder" class="boxSinv">
      <a id="embed" onclick="toggleEmbed(); return false;" title="<?php echo $i18n->get($action.'_embed'); ?>"></a>
      <a id="facebookShare" onclick="postToFeed(); return false;" title="<?php echo $i18n->get($action.'_facebook'); ?>"></a>
      <a id="twitterShare" onclick="newTweet(); return false;" title="<?php echo $i18n->get($action.'_twitter'); ?>"></a>
<?php if ($action == 'track') { ?>
      <a id="mailShare" title="<?php echo $i18n->get($action.'_mail'); ?>" href="mailto:?subject=<?php echo $i18n->get($action.'_mail_subject',array(esc($name),esc($artist))); ?>&body=<?php echo $i18n->get($action.'_mail_body', $link); ?>"></a>
<?php } else if ($action == 'album') { ?>
      <a id="mailShare" title="<?php echo $i18n->get($action.'_mail'); ?>" href="mailto:?subject=<?php echo $i18n->get($action.'_mail_subject',array(esc($album),esc($artist))); ?>&body=<?php echo $i18n->get($action.'_mail_body', $link); ?>"></a>
<?php } ?>
    </div>
    
    <div id="embedHolder" class="boxSinv" style="display: none;">
      <textarea readonly="readonly" id="embedContent" class="boxS5"><?php echo $iframeCode; ?></textarea>
    </div>
    <div style="clear: both"></div>
  
  </div>
<?php } // end display:none for iframeMode ?>
</div>