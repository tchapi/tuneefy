<div id="api" class="bdTop txtS">
  <h2 class="color apiTitle"><?php $i18n->api_intro; ?></h2>

  <div id="apiExplanation" class="apiBloc">
    <h3 class="color"><?php $i18n->api_overview_title; ?></h3>
    <div><?php $i18n->api_overview; ?></div>
  </div>
  
  <div id="apiEndpoint" class="apiBloc">
    <h3 class="color"><?php $i18n->api_endpoint_title; ?></h3>
    <p class="boxed boxS"><?php echo _API_URL; ?></p>
  </div>
   
  <div id="apiAuth" class="apiBloc">
    <h3 class="color"><?php $i18n->api_auth_title; ?></h3>
    <div><?php $i18n->api_auth; ?></div>
  </div>

  <div id="apiPlatforms" class="apiBloc">
    <h3 class="color"><?php $i18n->api_platforms_title; ?></h3>
    <div><?php $i18n->api_platforms(11); ?></div>
    <div class="platformsList boxed boxS">
      <table>
        <?php
          
          $rowPlatform = "";
          $rowC  = "";
          $rowS  = "";
          $rowAS = "";
          $rowL  = "";
        
          $platforms = API::getPlatforms();
          while (list($pId, $pObject) = each($platforms))
          {
            if ($pObject->isActiveForSearch()) {
            
              $rowPlatform .= "<th class=\"platformName\" >".$pObject->getName()."</th>";
              
              $imgBase = "<img src=\""._SITE_URL."/img/%s.png\" width=\"39px\" height=\"39px\"/>";
              $imgS  = $pObject->isActiveForSearch()?"success":"error";
              $imgAS = $pObject->isActiveForAlbumSearch()?"success":"error";
              $imgL  = $pObject->isActiveForLookup()?"success":"error";
              
              $rowC  .= "<td>".$pObject->getId()."</td>";
              
              $rowS  .= "<td>".sprintf($imgBase, $imgS)."</td>";
              $rowAS .= "<td>".sprintf($imgBase, $imgAS)."</td>";
              $rowL  .= "<td>".sprintf($imgBase, $imgL)."</td>";
            }
          }
          reset($platforms);
        ?>
        <thead>
          <tr class="platformsEnum">
            <th><?php $i18n->api_platforms_platform; ?></th><?php echo $rowPlatform; ?>
          </tr>
        </thead>
        <tbody>
          <tr class="ids"><td>id</td><?php echo $rowC; ?></tr>
          <tr><td><?php $i18n->api_platforms_search_tracks; ?></td><?php echo $rowS; ?></tr>
          <tr><td><?php $i18n->api_platforms_search_albums; ?></td><?php echo $rowAS; ?></tr>
          <tr><td><?php $i18n->api_platforms_lookup; ?></td><?php echo $rowL; ?></tr>
        </tbody>
      </table>
     </div>
  </div>
    
  <div id="apiMethods" class="apiBloc">
    <h3 class="color"><?php $i18n->api_methods_title; ?></h3>
    <p><?php $i18n->api_methods; ?></p>
    
    <h4 class="color"><a name="lookup">lookup</a></h4>
    <div class="method">
      <p><?php $i18n->api_lookup_description; ?></p>
      <h5><?php $i18n->api_arguments; ?></h5>
      <ul>
        <li><span class="parameter">q</span> - <span class="required"><?php $i18n->api_required; ?></span> : <?php $i18n->api_query_terms; ?></li>
        <li><span class="parameter">limit</span> - <span class="optional"><?php $i18n->api_optional; ?></span> : <?php $i18n->api_max_results; ?></li>
        <li><span class="parameter">alt</span> - <span class="optional"><?php $i18n->api_optional; ?></span> : <?php $i18n->api_alt; ?></li>
      </ul>
      <h5><?php $i18n->api_returns; ?></h5>
      <ul>
        <li><span class="parameter">lookedUpPlatform</span> - <span class="returnType"><?php $i18n->api_integer; ?></span> : <?php $i18n->api_lookedup_platform; ?></li>
        <li><span class="parameter">query</span> - <span class="returnType"><?php $i18n->api_string; ?></span> : <?php $i18n->api_query_cleaned; ?></li>
        <li><span class="parameter">lookedUpItem</span> - <span class="returnType"><?php $i18n->api_object; ?></span> : <?php $i18n->api_lookedup_item; ?></li>
      </ul>
      <h5><?php $i18n->api_ex_call; ?></h5>
      <p class="boxed boxS"><?php echo _API_URL; ?>/<span class="color">lookup?q=radiohead&limit=10&alt=json</span></p>
      <h5><?php $i18n->api_ex_response; ?></h5>
      <p class="boxed boxS response">{<br/>&nbsp;&nbsp;"provider":"tuneefy",<br/>&nbsp;&nbsp;"api":true,<br/>&nbsp;&nbsp;"version":"1.2a",<br/>&nbsp;&nbsp;"status":"splendid",<br/>&nbsp;&nbsp;"data":<br/>&nbsp;&nbsp;{<br/>&nbsp;&nbsp;&nbsp;&nbsp;"lookedUpPlatform":-1,<br/>&nbsp;&nbsp;&nbsp;&nbsp;"query":{<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"initial": "radiohead",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"transformed": radiohead"<br/>&nbsp;&nbsp;&nbsp;&nbsp;},<br/>&nbsp;&nbsp;&nbsp;&nbsp;"lookedUpItem":null<br/>&nbsp;&nbsp;}<br/>}</p>
      <h5><?php $i18n->api_ex_call; ?></h5>
      <p class="boxed boxS"><?php echo _API_URL; ?>/<span class="color">lookup?q=http://www.deezer.com/music/track/10240179&alt=json</span></p>
      <h5><?php $i18n->api_ex_response; ?></h5>
      <p class="boxed boxS response">{<br/>&nbsp;&nbsp;"provider":"tuneefy",<br/>&nbsp;&nbsp;"api":true,<br/>&nbsp;&nbsp;"version":"1.2a",<br/>&nbsp;&nbsp;"status":"splendid",<br/>&nbsp;&nbsp;"data":<br/>&nbsp;&nbsp;{<br/>&nbsp;&nbsp;&nbsp;&nbsp;"lookedUpPlatform":0,<br/>&nbsp;&nbsp;&nbsp;&nbsp;"query":{<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"initial":"http%3A%2F%2Fwww.deezer.com%2Fmusic%2Ftrack%2F10240179",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"transformed""Metronomy+The+Look"<br/>&nbsp;&nbsp;&nbsp;&nbsp;},<br/>&nbsp;&nbsp;&nbsp;&nbsp;"lookedUpItem":<br/>&nbsp;&nbsp;&nbsp;&nbsp;{<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"name":"The Look",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"artist":"Metronomy",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"album":"The English Riviera",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"picture":"http:\/\/api.deezer.com\/2.0\/album\/936365\/image",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link":"http:\/\/www.deezer.com\/music\/track\/10240179"<br/>&nbsp;&nbsp;&nbsp;&nbsp;}<br/>&nbsp;&nbsp;}<br/>}</p>
    </div>
    
    <h4 class="color"><a name="search">search</a></h4>
    <div class="method">
      <p><?php $i18n->api_search_description; ?></p>
      <h5><?php $i18n->api_arguments; ?></h5>
      <ul>
        <li><span class="parameter">q</span> - <span class="required"><?php $i18n->api_required; ?></span> : <?php $i18n->api_query_terms; ?></li>
        <li><span class="parameter">platform</span> - <span class="required"><?php $i18n->api_required; ?></span> : <?php $i18n->api_platform_search; ?></li>
        <li><span class="parameter">type</span> - <span class="required"><?php $i18n->api_required; ?></span> : <?php $i18n->api_type_search; ?></li>
        <li><span class="parameter">limit</span> - <span class="optional"><?php $i18n->api_optional; ?></span> : <?php $i18n->api_max_results; ?></li>
        <li><span class="parameter">alt</span> - <span class="optional"><?php $i18n->api_optional; ?></span> : <?php $i18n->api_alt; ?></li>
      </ul>
      <h5><?php $i18n->api_returns; ?></h5>
      <ul>
        <li><span class="parameter">(array of objects)</span> - <span class="returnType"><?php $i18n->api_object; ?></span> : <?php $i18n->api_track_results; ?></li>
      </ul>
      <h5><?php $i18n->api_ex_call; ?></h5>
      <p class="boxed boxS"><?php echo _API_URL; ?>/<span class="color">search?q=radiohead+creep&platform=0&type=track&limit=2&alt=json</span></p>
      <h5><?php $i18n->api_ex_response; ?></h5>
      <p class="boxed boxS response">{<br/>&nbsp;&nbsp;"provider":"tuneefy",<br/>&nbsp;&nbsp;"api":true,<br/>&nbsp;&nbsp;"version":"1.2a",<br/>&nbsp;&nbsp;"status":"splendid",<br/>&nbsp;&nbsp;"data":<br/>&nbsp;&nbsp;{[<br/>&nbsp;&nbsp;&nbsp;&nbsp;{<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title":"Creep",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"artist":"Radiohead",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"album":"The Best Of",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"picture":"http:\/\/api.deezer.com\/2.0\/album\/301747\/image",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link":"http:\/\/www.deezer.com\/music\/track\/3129324",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"score":1<br/>&nbsp;&nbsp;&nbsp;&nbsp;},<br/>&nbsp;&nbsp;&nbsp;&nbsp;{<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title":"Creep (Acoustic)",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"artist":"Radiohead",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"album":"Pablo Honey (Collector's Edition)",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"picture":"http:\/\/api.deezer.com\/2.0\/album\/302887\/image",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link":"http:\/\/www.deezer.com\/music\/track\/3147243",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"score":0.91<br/>&nbsp;&nbsp;&nbsp;&nbsp;}<br/>&nbsp;&nbsp;]}<br/>}</p>
      <h5><?php $i18n->api_ex_call; ?></h5>
      <p class="boxed boxS"><?php echo _API_URL; ?>/<span class="color">search?q=bjork&platform=10&type=album&limit=2&alt=json</span></p>
      <h5><?php $i18n->api_ex_response; ?></h5>
      <p class="boxed boxS response">{<br/>&nbsp;&nbsp;"provider":"tuneefy",<br/>&nbsp;&nbsp;"api":true,<br/>&nbsp;&nbsp;"version":"1.2a",<br/>&nbsp;&nbsp;"status":"splendid",<br/>&nbsp;&nbsp;"data":<br/>&nbsp;&nbsp;{[<br/>&nbsp;&nbsp;&nbsp;&nbsp;{<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title":null,<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"artist":"Bj\u00f6rk",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"album":"Post",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"picture":"http:\/\/cdn3.rd.io\/album\/5\/1\/a\/0000000000016a15\/square-200.jpg",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link":"http:\/\/rd.io\/x\/Qj5DNVY",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"score":1<br/>&nbsp;&nbsp;&nbsp;&nbsp;},<br/>&nbsp;&nbsp;&nbsp;&nbsp;{<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title":null,<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"artist":"Bj\u00f6rk",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"album":"Biophilia",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"picture":"http:\/\/cdn3.rd.io\/album\/0\/d\/7\/00000000000fd7d0\/square-200.jpg",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link":"http:\/\/rd.io\/x\/Qj5NiJM",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"score":0.91<br/>&nbsp;&nbsp;&nbsp;&nbsp;}<br/>&nbsp;&nbsp;]}<br/>}</p>
    </div>
    
     <h4 class="color"><a name="aggregate">aggregate</a></h4>
    <div class="method">
      <p><?php $i18n->api_aggregate_description; ?></p>
      <h5><?php $i18n->api_arguments; ?></h5>
      <ul>
        <li><span class="parameter">q</span> - <span class="required"><?php $i18n->api_required; ?></span> : <?php $i18n->api_query_terms; ?></li>
        <li><span class="parameter">type</span> - <span class="required"><?php $i18n->api_required; ?></span> : <?php $i18n->api_type_search; ?></li>
        <li><span class="parameter">limit</span> - <span class="optional"><?php $i18n->api_optional; ?></span> : <?php $i18n->api_max_results; ?></li>
        <li><span class="parameter">alt</span> - <span class="optional"><?php $i18n->api_optional; ?></span> : <?php $i18n->api_alt; ?></li>
      </ul>
      <h5><?php $i18n->api_returns; ?></h5>
      <ul>
        <li><span class="parameter">(array of objects)</span> - <span class="returnType"><?php $i18n->api_object; ?></span> : <?php $i18n->api_track_results; ?></li>
        <li><span class="parameter">(share link)</span> - <span class="returnType"><?php $i18n->api_string; ?></span> : <?php $i18n->api_share_link; ?></li>
      </ul>
      <h5><?php $i18n->api_warning; ?></h5>
      <p><?php $i18n->api_aggregate_warning; ?></p>
      <h5><?php $i18n->api_ex_call; ?></h5>
      <p class="boxed boxS"><?php echo _API_URL; ?>/<span class="color">aggregate?q=lana+del+rey&type=track&limit=2&alt=json</span></p>
      <h5><?php $i18n->api_ex_response; ?></h5>
      <p class="boxed boxS response">{<br/>&nbsp;&nbsp;"provider": "tuneefy",<br/>&nbsp;&nbsp;"api": true,<br/>&nbsp;&nbsp;"version": "1.2a",<br/>&nbsp;&nbsp;"status": "splendid",<br/>&nbsp;&nbsp;"data": {<br/>&nbsp;&nbsp;&nbsp;&nbsp;"1": {<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": "Born To Die",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"artist": "Lana Del Rey",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"album": "Born To Die",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"picture": "http://api.deezer.com/2.0/album/1503261/image",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link": {<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link0": "http://www.deezer.com/track/16150097",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link1": "http://open.spotify.com/track/2wMby9pciuGUIP8q7y4Yn6",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link13": "http://player.qobuz.com/#!/track/6997559",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link7": "http://youtube.com/watch?v=Bag1gUxuU0g"<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"score": 3.8199999999999998,<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"shareLink": "<?php echo _SITE_URL; ?>/include/share/share.php?itemType=0&name=Born+To+Die&artist=Lana+ ... Fv%3DBag1gUxuU0g"<br/>&nbsp;&nbsp;&nbsp;&nbsp;},<br/>&nbsp;&nbsp;&nbsp;&nbsp;"0": {<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": "Video Games",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"artist": "Lana Del Rey",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"album": "Video Games",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"picture": "http://api.deezer.com/2.0/album/1424452/image",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link": {<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link0": "http://www.deezer.com/track/15425694",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link2": "http://www.last.fm/music/Lana+Del+Rey/_/Video+Games",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link3": "http://tinysong.com/IfWx"<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"score": 3,<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"shareLink": "<?php echo _SITE_URL; ?>/include/share/share.php?itemType=0&name=Video+Games&artist=Lana+Del+Rey&album=Video+Games&image=htt ... 2Ftinysong.com%2FIfWx"<br/>&nbsp;&nbsp;&nbsp;&nbsp;}<br/>&nbsp;&nbsp;}<br/>}</p>
      <h5><?php $i18n->api_ex_call; ?></h5>
      <p class="boxed boxS"><?php echo _API_URL; ?>/<span class="color">aggregate?q=police&type=album&limit=2&alt=json</span></p>
      <h5><?php $i18n->api_ex_response; ?></h5>
      <p class="boxed boxS response">{<br/>&nbsp;&nbsp;"provider": "tuneefy",<br/>&nbsp;&nbsp;"api": true,<br/>&nbsp;&nbsp;"version": "1.2a",<br/>&nbsp;&nbsp;"status": "splendid",<br/>&nbsp;&nbsp;"data": [<br/>&nbsp;&nbsp;&nbsp;&nbsp;{<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": null,<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"artist": "The Police",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"album": "The Police",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"picture": "http://api.deezer.com/2.0/album/125975/image",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link": {<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link0": "http://www.deezer.com/fr/music/the-police/the-police-125975",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link1": "http://open.spotify.com/album/7rehtzREvbMdlkl9PQgJFW"<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"score": 1.8300000000000001,<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"shareLink": "<?php echo _SITE_URL; ?>/include/share/share.php?itemType=1&artist=The+Police&album=The+Police&image=http% ... vbMdlkl9PQgJFW"<br/>&nbsp;&nbsp;&nbsp;&nbsp;},<br/>&nbsp;&nbsp;&nbsp;&nbsp;{<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": null,<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"artist": "The Police",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"album": "The Very Best Of Sting And The Police",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"picture": "http://api.deezer.com/2.0/album/247629/image",<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link": {<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"link0": "http://www.deezer.com/fr/music/the-police/the-very-best-of-sting-and-the-police-247629"<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"score": 0.91000000000000003,<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"shareLink": "<?php echo _SITE_URL; ?>/include/share/share.php?itemType=1&artist=The+Police&album=The+Very ... ng-and-the-police-247629"<br/>&nbsp;&nbsp;&nbsp;&nbsp;}<br/>&nbsp;&nbsp;]<br/>}</p>
    </div>
    
  </div>
  
  <div id="apiDisclaimer" class="apiBloc">
    <h3 class="color"><?php $i18n->api_disclaimer_title; ?></h3>
    <div><?php $i18n->api_disclaimer; ?></div>
  </div>
 
</div>