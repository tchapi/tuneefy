<?php

  $platforms_html = "";
  
  $platforms = API::getPlatforms();
  
  while (list($pId, $pObject) = each($platforms))
  {
    if ($pObject->isActiveForSearch())
      $platforms_html .= "<a class=\"btns btn_".$pId." off\" rel=\"".$pId."\" id=\"pltf".$pId."\" title=\"".$pObject->getName()."\" ></a>";
  }
  reset($platforms);

?><div id="searchForm" class="wrap bdTop">
<?php if (!$mobile) { ?>    
  <!--<div id="new" ><img src="<?php echo _SITE_URL; ?>/img/<?php echo $i18n->newImage; ?>" width="116" height="60" title="<?php echo $i18n->newAlt; ?>"/></div>-->
  <div id="moreOptions" ><img src="<?php echo _SITE_URL; ?>/img/<?php echo $i18n->moreOptionsImage; ?>" width="160" height="60" title="<?php echo $i18n->moreOptionsAlt; ?>"/></div>
<?php } ?>
  <form id="find" action="">

    <div id="basic">
      <input type="text" id="query" name="query" class="boxS5" value="<?php echo isset($request)?html_entity_decode($request):$i18n->query_label; ?>" />
      <div id="searchType" class="boxed boxS"><div id="searchTypeInner">
        <span id="typeAlbums" class="off"><?php echo $i18n->_albums ;?></span>
        <span id="typeTracks"><?php echo $i18n->_tracks ;?></span>
        <input type="checkbox" id="searchTypeCheckbox" checked='checked' />
      </div></div>
      <input type="submit" id="launch" class="txtSinv boxS2" value="<?php echo $i18n->search_button; ?>"/>
      <div id="resetQuery" style="display: none;"></div>
      <div id="options"></div>
      <div id="waiting" style="display: none;"><img src="<?php echo _SITE_URL; ?>/img/ajax-loader.gif" /></div>
    </div>
<?php if (!$mobile) { ?>    
    <div id="help" style="display:none;" class="txtS">
      <div class="neverAgain"><span class="closeForever"><?php echo $i18n->help_text_close; ?></span><span class="closeHelp"></span></div>
      <div class="helpMe">
      <span class="color helpTitle"><?php echo $i18n->help_text_title; ?></span>
      <?php echo $i18n->help_text; ?>
      </div>
      <div class="moreHelp">
      <span><?php echo $i18n->help_text_more; ?></span>
      <a class="btn btnMoreHelp" href="<?php echo _SITE_URL; ?>/about#patterns"><?php echo $i18n->help_text_more_button; ?></a>
      </div>
      <div class="helpTriangle"></div>
    </div>
<?php } ?>
    <div id="hideMisere" style="display:none;"></div>
    <div id="advanced" style="display:none;" class="shd">
      <div id="availablePlatforms" class="boxS3">
        <span class="filter"><?php echo $i18n->available_platforms; ?></span>
        <?php echo $platforms_html;?>
      </div>
      <div id="mergeResults" class="boxS3">
        <span class="merge"><?php echo $i18n->merge_label; ?></span>
        <input type="checkbox" id="strictMode" checked="checked" />
      </div> 
    </div>
    
  </form>
</div>

<div id="alerts" class="wrap" ></div>
<div id="results" class="wrap" ></div>
