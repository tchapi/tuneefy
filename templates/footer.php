    </div>
  </div>
</div>
<?php if (!(($action == 'track' || $action == 'album') && $iframeMode)) { // end display:none for iframeMode ?>
<div id="footer-wrapper" class="full">
  <div id="footer" class="wrap bdTop txtS">
    
    <div class="endorsement bdBot"><?php echo $i18n->endorsement; ?></div>
    <div class="copycon bdTop">
<?php if (!$mobile) { ?>
      <div class="copyright"><img src="<?php echo _SITE_URL; ?>/img/logo_footer.png" width="115" alt="<?php echo $i18n->copyright; ?>" /></div>
<?php } ?>
      <div class="conditions"><?php echo $i18n->copyright; ?> &nbsp; <a href="<?php echo _SITE_URL; ?>/about#contact"><?php echo $i18n->contact; ?></a> &nbsp; <a href="https://twitter.com/tuneefy" target="_new" rel="nofollow" class="twitterLink"><?php $i18n->follow_twitter; ?></a></div>
    </div>
    
  </div>
</div>
<?php } // end display:none for iframeMode ?>
</body>
</html>