<div id="stats" class="bdTop txtS">
  <h2 class="color statsTitle"><?php $i18n->stats_title_long; ?></h2>
  
  <?php // echo '<div id="totalViews">'; ?>
    <?php // $i18n->total_tracks_viewed; echo $totalViews; echo '<br />'; ?>
    <?php // $i18n->total_links_clicked; echo $totalPlatformsHits; ?>
  <?php // echo '</div>'; ?>
  
  <div id="globalStats">
  <h2 class="color"><?php $i18n->global_stats; ?></h2>
   <?php 
    $li = "";
    $data = "";
    
    while (list($key, $val) = each($platformsHits))
    {
      $current = API::getPlatform($key);
    
      $li .= "<li>" . $current->getName() . "<span class=\"figure color\">" . sprintf("%01.1f",$platformsHits[$key]/$totalPlatformsHits*100) ."%</span></li>" ;
      $data .= "<tr><th scope=\"row\"><span class=\"id\">". $key ."</span><span class=\"name\">". $current->getName() ."</span><span class=\"color\">". $current->getColor() ."</span></th><td>" . sprintf("%01d",$platformsHits[$key]/$totalPlatformsHits*100) ."%</td></tr>" ;
    }
  
  ?>
  <table style="display:none;" id="pieData">
    <tbody>
      <?php echo $data; ?>
    </tbody>
  </table>
  <div id="pieChart"></div>
  <ul>
    <?php echo $li; ?>
  </ul>
  </div>
  
  <div id="mostViewedArtists" class="boxed boxS">
  <h2 class="color"><?php $i18n->most_viewed_artists($listDisplayLimit); ?></h2>
    <ul>
    <?php 

      $maxWidth = 180;
      
      while (list($key, $val) = each($mostViewedArtists))
      {
        
        $currentPercent = intval($mostViewedArtists[$key])/intval(current($topViewedArtist));
        
        $currentColor['r'] = dechex(118*$currentPercent + (1-$currentPercent)*255);
        $currentColor['g'] = dechex(186*$currentPercent + (1-$currentPercent)*255);
        $currentColor['b'] = dechex(208*$currentPercent + (1-$currentPercent)*255);
        
        echo "<li><span class=\"name\">" . $key . "</span><div class=\"bar Fcolor\" style=\"width: ". floor($currentPercent*$maxWidth) ."px; background: #".$currentColor['r'].$currentColor['g'].$currentColor['b'].";\"></div><span class=\"count\">";
          $i18n->views(intval($mostViewedArtists[$key]));
        echo "</span></li>" ;
      }
  
    ?>
    </ul>
  </div>
  
  <div id="mostViewedTracks">
  <h2 class="color"><?php echo $i18n->most_viewed_tracks($listDisplayLimit); ?></h2>
    <ul>
    <?php 
      
      $order = 0;
      
      while (list($key, $val) = each($mostViewedTracks))
      {
        list($id, $artist, $title) = explode('|', html_entity_decode($key));
        $fontSize = 2.3*(1-$order/5) + ($order/5)*0.6;
        
        echo "<li><span class=\"count bdBot\">";
          $i18n->views(intval($mostViewedTracks[$key]));
        echo "</span><span class=\"order bdTop bdBot\">".(++$order)."</span><span class=\"title\"><a style=\"font-size: ".$fontSize."em\" href=\"" ._SITE_URL."/t/". DBUtils::toUid($id,_BASE_MULTIPLIER) . "\">" . ellipsis($title, 30) . "</a></span><span class=\"artist bdTop\">".$artist."</span></li>" ;
      }
  
    ?>
    </ul>
  </div>
  
  <div id="mostViewedAlbums">
  <h2 class="color"><?php echo $i18n->most_viewed_albums($listDisplayLimit); ?></h2>
    <ul>
    <?php 
      
      $order = 0;
      
      while (list($key, $val) = each($mostViewedAlbums))
      {
        list($id, $artist, $title) = explode('|', html_entity_decode($key));
        $fontSize = 2.3*(1-$order/5) + ($order/5)*0.6;
        
        echo "<li><span class=\"count bdBot\">";
          $i18n->views(intval($mostViewedAlbums[$key]));
        echo "</span><span class=\"order bdTop bdBot\">".(++$order)."</span><span class=\"title\"><a style=\"font-size: ".$fontSize."em\" href=\"" ._SITE_URL."/a/". DBUtils::toUid($id,_BASE_MULTIPLIER) . "\">" . ellipsis($title, 30) . "</a></span><span class=\"artist bdTop\">".$artist."</span></li>" ;
      }
  
    ?>
    </ul>
  </div>
</div>