<?php 

// Creating the bookmarklet code

// minifying it
$str     = file_get_contents(_PATH."/widget/bookmarklet.js");
$order   = array("\r\n", "\n", "\r", " ");
$replace = '';

// still minifying it
$minified = str_replace($order, $replace, $str);
$minified = str_replace("%s%",_SITE_URL, $minified);

?>
  <div id="about-wrapper" class="bdTop bdBot txtS">
  <h2 class="color aboutTitle"><?php $i18n->about_title_long; ?></h2>
  
  <div class="aboutRow boxed boxS">
    <div class="item1 img_info"><?php $i18n->facts_info; ?></div>
    <div class="item2 img_friends"><?php $i18n->facts_friends; ?></div>
  </div>
  
  <div class="aboutRow">
    <div class="item1 img_pertinence"><?php $i18n->facts_pertinence; ?></div>
    <div class="item2 img_widget"><?php $i18n->facts_minify($minified); ?></div>
  </div>
  
  <div class="aboutRow boxed boxS">
    <div class="item1 img_patterns"><a name="patterns"><?php $i18n->facts_supported; ?></a>
    </div>
    <div class="item2 txtS" id="examples">
      <?php $i18n->facts_supported_list; ?>
      </div>
  </div>
  
  <div class="aboutRow">
    <div class="item1 img_picks"><?php $i18n->facts_picks; ?></div>
    <div class="item2 img_free"><?php $i18n->facts_free; ?></div>
  </div>
  
  </div>

  <div id="os-wrapper" class="bdTop bdBot txtS">
  <h2 id="open-source" class="color aboutTitle"><?php $i18n->os_title_long; ?></h2>
    <div class="aboutRow boxed boxS">
      <h2 class="os_subtitle"><?php $i18n->os_paragraph_1_important; ?></h2>
      <div class="os_box"><?php $i18n->os_paragraph_1; ?></div>
      <div class="os_box"><?php $i18n->os_paragraph_2; ?></div>
      <div class="os_subtitle"><?php $i18n->os_paragraph_2_thanks; ?></div>
    </div>
  </div>

  <div id="team-wrapper" class="bdTop txtS">
  <a name="us"></a>
  <div class="theTeam">
    <h2 class="color aboutTitles"><?php $i18n->the_team; ?></h2>
    <a href="https://about.me/tchap" target="_blank"><div class="creator idea"><?php $i18n->facts_team_idea; ?></div></a>
    <a href="https://twitter.com/#!/_W___" target="_blank"><div class="creator design"><?php $i18n->facts_team_design; ?></div></a>
  </div>
  <div class="contactUs">
    <a name="contact"></a>
    <h2 class="color aboutTitles"><?php $i18n->contact_us; ?></h2>
    <div id="contactFormWrapper">
      <form class="contactForm">
        <label for="email"><?php $i18n->contact_us_email; ?></label><input type="text" id="email" name="email" class="boxS5" />
        <label for="message"><?php $i18n->contact_us_message; ?></label><textarea  id="message" name="message" class="boxS5" ></textarea>
        <a id="send" class="btn btnSendEmail"><?php $i18n->contact_us_send; ?></a>
      </form>
      <div class="waitingMail"><?php $i18n->sending_mail; ?></div>
      <div class="successMail"><?php $i18n->success_mail; ?></div>
      <div class="errorMail"><?php $i18n->error_mail; ?></div>
    </div>
  </div>

</div>