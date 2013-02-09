<?php
  
  require('../config.php');

  require(_PATH.'include/database/DBUtils.class.php');
  require(_PATH.'include/database/DBStats.class.php');
  require(_PATH.'include/database/DBConnection.class.php');

  $action = 'admin';

  // We get the first date and the last date
  $startDate = DBStats::firstDateInDB();
  $endDate = date("Y-m-d H:i:s"); // NOW()
  
  // In case we clicked 'refresh'
  if (isset($_REQUEST['start']) && isset($_REQUEST['end'])) {
  
    $startDate = strftime("%Y-%m-%d %H:%M:%S", strtotime($_REQUEST['start']));
    $endDate = strftime("%Y-%m-%d %H:%M:%S", strtotime($_REQUEST['end']));
  
  }
  
  // We get the catalogue span
  $platformsCatalogueSpan = DBStats::platformsCatalogueSpan($startDate, $endDate);
  
  // Number of tracks and albums
  $nbOfAlbums = DBStats::getNbOfAlbums();
  $nbOfTracks = DBStats::getNbOfTracks();
  
  // Most Viewed TRACKS AND ARTISTS
  $limite = 20;
  $mostViewedArtists = DBStats::mostViewedArtists($startDate, $endDate, $limite); // STANDARD STAT FUNC
  $mostViewedTracks = DBStats::mostViewedTracks($startDate, $endDate, $limite); // STANDARD STAT FUNC

  // Platforms HITS
  $platformsHits = DBStats::platformsHits($startDate, $endDate, 'all'); // STANDARD STAT FUNC
  $platformsHitsViaSearch = DBStats::platformsHits($startDate, $endDate, 'search'); // STANDARD STAT FUNC
  $platformsHitsViaShare = DBStats::platformsHits($startDate, $endDate, 'share'); // STANDARD STAT FUNC
  $totalPlatformsHits = DBStats::totalPlatformsHits($startDate, $endDate, 'all'); // STANDARD STAT FUNC
  $totalPlatformsHitsViaSearch = DBStats::totalPlatformsHits($startDate, $endDate, 'search'); // STANDARD STAT FUNC
  $totalPlatformsHitsViaShare = DBStats::totalPlatformsHits($startDate, $endDate, 'share'); // STANDARD STAT FUNC
  
  // Search Trends
  $lastSearches = DBStats::lastSearches($startDate, $endDate, $limite);
  $popularSearches = DBStats::popularSearches($startDate, $endDate, $limite);
  
  // Emails from coming_soon
  $mailsRegistered = DBStats::totalEmails();
  
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
  
    $(".errorButton").click(function(){
      $.get("minify.php?mode=errors",function(data){
        if (data)
          $.modal(data);
        else
          $.modal("<div class=\"modal\">No errors</div>", {overlayClose: true});
      });
    });
    
    $(".watchdogButton").click(function(){
      $.get("watchdog.php",function(data){
        $.modal("<div class=\"modal\">" + data + "</div>", {overlayClose: true});
      });
    });
    
    $(".mailButton").click(function(){
      $.get("mails.php",function(data){
        $.modal("<div class=\"modal\"><ul>" + data + "</ul></div>", {overlayClose: true});
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
        <a href="/"><h1 class="logo color"><img src="<?php echo _SITE_URL; ?>/img/logo.png" width="267" height="133" alt="tuneefy"/></h1></a>
        <p class="tagline txtS">Administration <span class="color">panel</span></p>
      </div>
      
      <div class="wrap">

<?php // == == == == == == ACTIONS == == == == == == // ?>

        <div id="actions" class="bdTop bdBot">
          <div class="margins">
            <h2 class="txtS">Actions</h2>
            <div class="boxed">
            <p class="txtS">Check tuneefy sanity : <a class="btn watchdogButton">Run Watchdog</a></p>
            <p class="txtS">Get minified JS : <a class="btn errorButton">Errors</a><a class="btn" href="minify.php?adv=0" target="_blank">Output</a><a class="btn" href="minify.php?adv=0&file=1">File (.js)</a></p>
            <p class="txtS">Currently <span class="color"><?php echo $mailsRegistered; ?></span> emails in the database : <a class="btn mailButton">mail admin</a></p>
            <p class="txtS">List the <span class="color"><?php echo $nbOfTracks; ?></span> tracks in the database : <a class="btn" href="list.php?type=tracks">list tracks</a></p>
            <p class="txtS">List the <span class="color"><?php echo $nbOfAlbums; ?></span> albums in the database : <a class="btn" href="list.php?type=albums">list albums</a></p>
            </div>
          </div>
        </div>

<?php // == == == == == == DATE SPAN == == == == == == // ?>

        <div id="dates" class="bdTop bdBot">
          <form id="admin_refresh" method="POST" action="index.php" class="margins">
            <img src="<?php echo _SITE_URL; ?>/img/admin_stats.png" class="stats_img" width="48" height="48"/>
            <span class="color titleStats">Tracks Statistics</span>
            <label for="start">From : </label>
            <input type="text" id="start" name="start" value="<?php echo $startDate; ?>"/>
            
            <label for="end">To : </label>
            <input type="text" id="end" name="end" value="<?php echo $endDate; ?>"/>
            
            <input type="submit" id="datespan" value="Refresh"/>
            
          </form>
        </div>
        
<?php // == == == == == == PLATFORMS TRENDS == == == == == == // ?>

        <div id="admin_platformsTrends" class="bdTop bdBot">
          <div class="margins">
            <h2 class="txtS">Tracks Statistics >> Listening usages</h2>
            <div class="boxed">
            <table width="100%">
            <thead>
              <tr class="headerTable">
                <th colspan="2" width="20px">Target platforms</th>
                <th>Listen via Share</th>
                <th>Listen via Search</th>
                <th>Total listenings</th>
                <th>Available links</th>
              </tr>
            </thead>
            <tbody>
        <?php
          
          $platforms = API::getPlatforms();
        
          while (list($pId, $pObject) = each($platforms))
          {
            $id = $pObject->getId();
            $phvsh = (isset($platformsHitsViaShare[$id]))?intval($platformsHitsViaShare[$id]):0;
            $phvse = (isset($platformsHitsViaSearch[$id]))?intval($platformsHitsViaSearch[$id]):0;
            if (!isset($platformsHits[$id])) $platformsHits[$id] = 0;
            
            echo "<tr><td width=\"35px\" class=\"image\"><div class=\"adminIcon\" style=\"background: url("._SITE_URL."/img/platforms/platform_".$id.".png)\"></div></td><td class=\"name\">".$pObject->getName()."</td>";
            echo "<td><span class=\"color number\">".$phvsh."</span> (" . sprintf("%01.1f",$phvsh/$totalPlatformsHitsViaShare*100) ."%)</td>";
            echo "<td><span class=\"color number\">".$phvse."</span> (" . sprintf("%01.1f",$phvse/$totalPlatformsHitsViaSearch*100) ."%)</td>";
            echo "<td><span class=\"color number\">".intval($platformsHits[$id])."</span> (" . sprintf("%01.1f",$platformsHits[$id]/$totalPlatformsHits*100) ."%)</td>" ;
            echo "<td><span class=\"color\">".$platformsCatalogueSpan[$id]."</span> ".sprintf("(%01.1f%%)",$platformsCatalogueSpan[$id]/$platformsCatalogueSpan['total']*100)."</td></tr>";
          }
          reset($platforms);
          
          echo "<tr class=\"sumup\"><td colspan=\"2\">Total</td>";
          echo "<td><span class=\"color number\">".$totalPlatformsHitsViaShare."</span> (" . sprintf("%01.1f",$totalPlatformsHitsViaShare/$totalPlatformsHits*100) ."%)</td>";
          echo "<td><span class=\"color number\">".$totalPlatformsHitsViaSearch."</span> (" . sprintf("%01.1f",$totalPlatformsHitsViaSearch/$totalPlatformsHits*100) ."%)</td>";
          echo "<td><span class=\"color number\">".$totalPlatformsHits."</span></td>";
          echo "<td><span class=\"color\">".$platformsCatalogueSpan['total']."</span></td>";
          
        ?>
            </tbody>
          </table>
          </div>
        </div>

<?php // == == == == == == TRACKS TRENDS == == == == == == // ?>
        <div id="admin_trackTrends" class="bdTop bdBot">
          <div class="margins">
            <h2 class="txtS">Tracks Statistics >> Most Viewed Artists</h2>
            <div class="boxed">
            <ol class="classement">
            <?php 
            
              while (list($key, $val) = each($mostViewedArtists))
              {
                echo "<li><span class=\"classement_in\">" . html_entity_decode($key) . " : <span class=\"color\">" . intval($val) . "</span></span></li>" ;
              }
            
            ?>
            </ol>
          </div>
          <div class="clear" style="height: 10px"></div>
          <h2 class="txtS">Tracks Statistics >> Most Viewed Tracks</h2>
            <div class="boxed">
            <ol class="classement">
            <?php 
            
              while (list($key, $val) = each($mostViewedTracks))
              {
                list($id, $artist, $title) = explode("|", $key);
                echo "<li><span class=\"classement_in\">" . html_entity_decode($artist) . " - <span class=\"link\"><a href=\"" . _SITE_URL."/t/".DBUtils::toUid($id, _BASE_MULTIPLIER). "\">" . html_entity_decode($title) . "</a></span> : <span class=\"color\">" . intval($val) . "</span></span></li>" ;
              }
            
            ?>
            </ol>
          </div>
          </div>
        </div>

<?php // == == == == == == SEARCH TRENDS == == == == == == // ?>

        <div id="admin_searchTrends" class="bdTop bdBot">
          <div class="margins">
            <h2 class="txtS">Tracks Statistics >> Latest Searches</h2>
            <div class="boxed">
            <ol class="classement">
          <?php 
          
            for($i=0; $i<count($lastSearches); $i++){
              echo "<li><span class=\"classement_in link\"><a href=\"" . _SITE_URL."/?q=".urlencode(html_entity_decode($lastSearches[$i])). "\">" . html_entity_decode($lastSearches[$i]) . "</a></span></li>\n";
            }
          
          ?>
            </ol>
            </div>
            <div class="clear" style="height: 10px"></div>
            <h2 class="txtS">Tracks Statistics >> Most Popular Search terms</h2>
            <div class="boxed">
            <ol class="classement">
          <?php 
              
            while (list($key, $val) = each($popularSearches))
            {
              echo "<li><span class=\"classement_in\"><span class=\"link\"><a href=\"" . _SITE_URL."/?q=".urlencode(html_entity_decode($key)). "\">" . html_entity_decode($key) . "</a></span> : <span class=\"color\">" . intval($popularSearches[$key]) . "</span></span></li>" ;
            }
          
          ?>
            </ol>
            </div>
          </div>
        </div>

<?php // == == == == == == == == == == == == == == == // ?>
        <div class="bdTop"></div>
               
      </div>
    </div>
  </div>
</div>
</body>
</html>