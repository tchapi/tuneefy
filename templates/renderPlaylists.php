<div id="playlists-wrapper" class="bdTop txtS">
  <h2 class="color playlistsTitle"><?php $i18n->playlists_title; ?></h2>

</div>
<div>
<form id="playlist-login" action="">

  <div id="login">
    <div id="explication"><?php echo $i18n->playlists_login_label; ?></div>
    <div id="loginButtons">
      <!-- DEEZER -->
        <div id="dz-root"></div>
        <script src="http://cdn-files.deezer.com/js/min/dz.js"></script>
        <script>
          DZ.init({
            appId  : '105411',
            channelUrl : '<?php echo _SITE_URL; ?>/channels/deezer.html'
          });
        </script>
      <a class="btns_full btn_full_0 direct" title="Deezer" rel="0"></a>
      <!-- DEEZER END -->
      <a class="btns_full btn_full_13 notDirect" title="Qobuz" rel="13" ></a>
      <a class="btns_full btn_full_3 notDirect" title="Grooveshark" rel="3" ></a>
      <a class="btns_full btn_full_7 notDirect" title="Youtube" rel="7" ></a>
      <a class="btns_full btn_full_10 notDirect" title="Rdio" rel="10" ></a>
    </div>
    <div id="loginFields" style="display: none;">
      <input type="text" id="username" name="username" class="boxS5 playlists" value="" />
      <input type="password" id="passwd" name="passwd" class="boxS5 playlists" value="" />
      <input type="hidden" id="platform" name="platform" />
      <input type="submit" id="log" class="txtSinv boxS2 playlists" value="<?php echo $i18n->playlists_login_button; ?>"/>
      <input type="reset" id="back" class="txtSinv boxS2 playlists" value="<?php echo $i18n->playlists_cancel_button; ?>"/>
    </div>
    <div id="logged" style="display: none;">
      <div id="loggedExplication"><?php echo $i18n->playlists_logged_label; ?></div>
      <input type="reset" id="logout" class="txtSinv boxS2 playlists" value="<?php echo $i18n->playlists_logout_button; ?>"/>
    </div>
  </div>

</form>
<form id="find" action="" style="display: none;">

  <div id="basic">
    <input type="text" id="query" name="query" class="boxS5 playlists" value="<?php echo $i18n->playlists_query_label; ?>" />

    <input type="submit" id="launch" class="txtSinv boxS2 playlists" value="<?php echo $i18n->playlists_button; ?>"/>
    <div id="resetQuery" style="display: none;" class="playlists"></div>

    <div id="waiting" style="display: none;" class="playlists"><img src="<?php echo _SITE_URL; ?>/img/ajax-loader.gif" /></div>
  </div>

</form>
</div>

<div id="alerts" class="wrap" ></div>
<div id="results" class="wrap" ></div>