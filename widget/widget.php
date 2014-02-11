<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# tuneefy: http://ogp.me/ns/fb/tuneefy#">

<?php // Encoding // ?>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php // jQuery // ?>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>

<?php // Style // ?>
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo _SITE_URL; ?>/css/reset.css" />
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo _SITE_URL; ?>/widget/widget.css" />

<?php $jsVersion = 6; ?>
<?php // DEFAULTS // ?>
<?php

  $defaultPlatforms = "";
  $allPlatforms = "";
  $jsAllPlatforms = "";
  
  $platforms = API::getPlatforms();

  while (list($pId, $pObject) = each($platforms))
  {
    $allPlatforms .= $pObject->isActiveForSearch()?$pObject->getId().",":"";
    $jsAllPlatforms .= $pObject->isActiveForSearch()?'$p['.$pObject->getId().'] = "'.$pObject->getName().'";':"";
  }
  reset($platforms);
?>
  <script type="text/javascript">
    $p = [];<?php echo $jsAllPlatforms; ?>
    $all_platforms = <?php echo '"'.substr($allPlatforms,0, -1).'"'; ?>;
    $table_link_prefix = <?php echo '"'._TABLE_LINK_PREFIX.'"'; ?>;
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
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/dev/UI/AlertUI.class.js?v=<?=$jsVersion?>"></script>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/dev/UI/ResultRenderUI.class.js?v=<?=$jsVersion?>"></script>
<?php } else { ?>
  <script type="text/javascript" src="<?php echo _SITE_URL; ?>/js/min/tuneefy.min.js?v=<?=$jsVersion?>"></script>
<?php } ?>

<?php // Javascript main entry point // ?>
  <script type="text/javascript">
  
  $(document).ready(function() {

    var controller = new ControlFreak();

    var model = new Tuneefy(controller);
    var alertView = new AlertUI(controller, 0);
    var resultsView = new ResultRenderUI(model, controller, 1, 1, true);
    
    $results_found = $result_found_widget;
    $(controller).trigger("tuneefy.search.start",["<?php echo html_entity_decode($request); ?>", 0, true, $all_platforms, 50]);

  });

  </script>

</head>
<body> 
  
  <div id="wrapper">
    <div id="waiting"><img src="<?php echo _SITE_URL; ?>/img/ajax-loader-widget.gif" /></div>
    
    <div id="results"></div>
    <div id="more"><a href="<?php echo _SITE_URL."/?q=".urlencode($request) ; ?>" target="_blank" class="btn"><?php echo $i18n->see_more; ?></a></div>
    <div id="alerts"></div>
  </div>

</body>
</html>