<?php
  
  require('../config.php');
  require(_PATH.'include/langs/i18nHelper.php');

  require(_PATH.'include/database/DBUtils.class.php');
  require(_PATH.'include/database/DBStats.class.php');
  require(_PATH.'include/database/DBConnection.class.php');

  $action = 'admin';

  // In case we clicked 'pagination'
  if (isset($_REQUEST['start'])) {
  
    $startOffset = intval($_REQUEST['start']);
  
  } else $startOffset = 0;
  
  if (isset($_REQUEST['limit'])) {
  
    $limit = intval($_REQUEST['limit']);
  
  } else $limit = 20;
  
  // We get the tracks
  if (isset($_REQUEST['type']) && $_REQUEST['type'] == "tracks") {
    $type = _TABLE_TRACK;
    $items = DBStats::getTracks($startOffset, $limit);
    $postType = 'tracks';
  } else {
    $type = _TABLE_ALBUM;
    $items = DBStats::getAlbums($startOffset, $limit);
    $postType = 'albums';
  }
  
?><!DOCTYPE html>
<html>
<head>
  <title>Administration</title>
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo _SITE_URL; ?>/css/reset.css" />
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo _SITE_URL; ?>/css/general.css" />
  <link rel="stylesheet" type="text/css" media="all" href="<?php echo _SITE_URL; ?>/css/style.css" />
  <link rel="stylesheet" type="text/css" media="all" href="admin.css" />
  
<?php // jQuery // ?>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
  <script type="text/javascript" src="jquery.simplemodal.1.4.2.min.js"></script>
  <script type="text/javascript">
  
  $(document).ready(function(){
    
    $(".addAPick").click(function(e){
    
      var idtrack = $(e.target).attr('rel');
    
      $.get("addAPick.php",{id: idtrack},function(data){
        $.modal("<div class=\"modal\">" + data + "</div>", {overlayClose: true});
      });
    });
  });
  
  </script>
  
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body class="Bcolor">
<div id="wrapper">
  <div id="content-wrapper" class="full">
    <div class="wrap clear bdBot" id="content">
    
      <div id="tagline" class="bdBot">
        <a href="/"><h1 class="logo color"><img src="<?php echo _SITE_URL; ?>/img/logo.png" width="267" height="133" alt="<?php $i18n->general_title; ?>"/></h1></a>
        <p class="tagline txtS">Administration <span class="color">panel</span></p>
      </div>
      
      <div class="wrap">

<?php // == == == == == == DATE SPAN == == == == == == // ?>

        <div id="dates" class="bdTop bdBot">
          <form id="admin_refresh" method="POST" action="list.php?type=<?php echo $postType; ?>" class="margins">
            <img src="<?php echo _SITE_URL; ?>/img/admin_stats.png" class="stats_img" width="48" height="48"/>
            <span class="color titleStats"><?php echo ucfirst($_REQUEST['type']); ?> Listing</span>
            <label for="start">Offset : </label>
            <input type="text" id="start" name="start" value="<?php echo $startOffset; ?>"/>
            
            <label for="end">Limit : </label>
            <input type="text" id="limit" name="limit" value="<?php echo $limit; ?>"/>
            <input type="submit" id="datespan" value="Refresh"/>
            
          </form>
        </div>
        
<?php // == == == == == == PLATFORMS TRENDS == == == == == == // ?>

        <div id="admin_platformsTrends" class="bdTop">
          <div class="margins">
            <h2 class="txtS"><?php echo ucfirst($_REQUEST['type']); ?> Listing (<?php echo $startOffset; ?> to <?php echo $startOffset + $limit; ?>)</h2>
            <div class="boxed">
            <table width="100%">
            <thead>
              <tr class="headerTable name">
                <th colspan="2" width="20px" class="name">Item</th>
                <th class="name">Artist</th>
                <th class="name">Album</th>
                <th class="name" width="52px"></th>
              </tr>
            </thead>
            <tbody>
        <?php
          while (list($id, $item) = each($items))
          {
            
            echo "<tr><td width=\"35px\" class=\"image\"><img src=\"".$item['image']."\" width=\"30px\" /></td><td class=\"name\">";
            
            if ($type == _TABLE_TRACK)
              echo "<a href=\""._SITE_URL.'/t/'.DBUtils::toUid($id, _BASE_MULTIPLIER)."\" target=\"_blank\">".html_entity_decode($item['name'])."</a></td>";
            else
              echo "<a href=\""._SITE_URL.'/a/'.DBUtils::toUid($id, _BASE_MULTIPLIER)."\" target=\"_blank\">".html_entity_decode($item['album'])."</a></td>";
              
            echo "<td class=\"name\">".html_entity_decode($item['artist'])."</td>";
            
            if ($type == _TABLE_TRACK)
              echo "<td class=\"name\">".ellipsis(html_entity_decode($item['album']),40)."</td>";
            else
              echo "<td class=\"name\"></td>";
              
            echo "<td class=\"name addAPick\" rel=\"".$id."\">pick it</td></tr>";
          }
        ?>
            </tbody>
          </table>
          </div>
        </div>
               
      </div>
    </div>
  </div>
</div>
</body>
</html>