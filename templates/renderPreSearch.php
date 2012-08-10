<div id="preSearch" class="bdBot bdTop">

  <div class="boxed boxS">
  
  <div id="info">
    <h2 class="infoTitle color"><?php echo $i18n->info_welcome; ?></h2>
    <p class="infoContent"><?php echo $i18n->info_content; ?></p>
    <a class="btn btn_more" href="<?php echo _SITE_URL; ?>/about"><?php echo $i18n->more_info; ?></a>
  </div>
<?php if (!$mobile) { ?>

  <div id="todaysPickOverlay"></div>
  <div id="pickPager">
    <div class="pickPagerItem Bcolor active"></div>
    <div class="pickPagerItem Bcolor"></div> 
    <?php if (isset($weHaveAPick[0])) { ?>
      <div class="pickPagerItem Bcolor"></div>  
    <?php } ?> 
  </div>

<?php if (isset($weHaveAPick)) { ?>
  <div id="todaysPick">
  
<?php if (isset($weHaveAPick[0])) { ?>
  <div class="pick">
    <h2 class="pickTitle color"><?php echo $i18n->pick_of_the_day; ?></h2>
    <div class="pickContent">
      <div class="cover">
        <img src="<?php echo ($tp_image[0] == "null")?_SITE_URL."/img/nothumb_track.png":$tp_image[0]; ?>" width="50" height="50" />
        <span class="coverlay"></span>
      </div>
      <div class="info">
        <div class="title"><?php echo ellipsis($tp_name[0],24); ?></div>
        <div class="artist"><?php echo ellipsis($tp_artist[0], 40); ?></div>
        <div class="album"><?php echo ($tp_album[0] == "null")?$i18n->various_albums:ellipsis($tp_album[0],36); ?></div>
      </div>
    </div>
    
    <a class="btn btn_picks" href="<?php echo $tp_link[0]; ?>"><?php echo $i18n->pick_discover; ?></a>
  </div>
<?php } ?>
<?php if (isset($weHaveAPick[1])) { ?>
  <div class="pick">
    <h2 class="pickTitle color"><?php echo $i18n->most_viewed_this_week; ?></h2>
    <div class="pickContent">
      <div class="cover">
        <img src="<?php echo ($tp_image[1] == "null")?_SITE_URL."/img/nothumb_track.png":$tp_image[1]; ?>" width="50" height="50" />
        <span class="coverlay"></span>
      </div>
      <div class="info">
        <div class="title"><?php echo ellipsis($tp_name[1],24); ?></div>
        <div class="artist"><?php echo ellipsis($tp_artist[1], 40); ?></div>
        <div class="album"><?php echo ($tp_album[1] == "null")?$i18n->various_albums:ellipsis($tp_album[1],36); ?></div>
      </div>
    </div>
    
    <a class="btn btn_picks" href="<?php echo $tp_link[1]; ?>"><?php echo $i18n->pick_discover; ?></a>
  </div>
<?php } ?>
<?php if (isset($weHaveAPick[2])) { ?>
  <div class="pick">
    <h2 class="pickTitle color"><?php echo $i18n->last_track_shared; ?></h2>
    <div class="pickContent">
      <div class="cover">
        <img src="<?php echo ($tp_image[2] == "null")?_SITE_URL."/img/nothumb_track.png":$tp_image[2]; ?>" width="50" height="50" />
        <span class="coverlay"></span>
      </div>
      <div class="info">
        <div class="title"><?php echo ellipsis($tp_name[2],24); ?></div>
        <div class="artist"><?php echo ellipsis($tp_artist[2], 40); ?></div>
        <div class="album"><?php echo ($tp_album[2] == "null")?$i18n->various_albums:ellipsis($tp_album[2],36); ?></div>
      </div>
    </div>
    
    <a class="btn btn_picks" href="<?php echo $tp_link[2]; ?>"><?php echo $i18n->pick_discover; ?></a>
  </div>
<?php } ?>

  </div>
  
<?php } ?>
<?php } ?>

  </div>
  
</div>