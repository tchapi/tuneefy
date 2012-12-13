<?php

// Header
$lang['general_title']= "tuneefy"; 
$lang['description']= "tuneefy is a new unified way to share music with your friends, over various online music services ! ";
$lang['tagline']= "Sharing <span class='color'>music</span>, done <span class='color'>right</span>.";
$lang['tags']= "listen, music, online, share, unify, platform, friends, tuneefy, music service, music platform, track, sharing";
$lang['search_title']= "Your search for %s"; 

// Menu
$lang['home_title']= "Home"; 
$lang['home_tip']= "Yay ! Search and Share Music !"; 
$lang['stats_title']= "Trends"; 
$lang['stats_tip']= "Stats & stuff"; 
$lang['about_title']= "About"; 
$lang['about_tip']= "What is it, yo ?"; 

// API
$lang['api_title']="API";
$lang['api_intro']="Introducing the tuneefy API";
$lang['api_overview_title'] = "Overview";
$lang['api_overview'] = "<p>The tuneefy API is a RESTful API that allows you to lookup, search, and aggregate tracks via tuneefy.</p><p>Two response formats are available : </p><ul><li><span class=\"color\">JSON</span></li><li><span class=\"color\">XML</span></li></ul><p>The server will first check if an '<span class=\"color\">HTTP_ACCEPT</span>' header is present, containing one of the two supported MIME-types. The response type can be overriden with the 'alt' parameter available for each method (see below). By default, the API will return XML.</p><p>All the responses are <span class=\"color\">UTF-8</span> encoded, and so must be all the calls.</p>";
$lang['api_endpoint_title'] = "API Endpoint";
$lang['api_auth_title'] = "Authentication";
$lang['api_auth'] = "<p>Every request to the API must be signed with <span class=\"color\"><a href='http://oauth.net/documentation/getting-started/' target='_blank'>2-legged OAuth</span></a> authentication.</p><p>OAuth allows a server to accept requests only from a client that knows the consumer secret. This is done by a requiring a signature that's a hash of:</p>

<ul>
<li>the HTTP method, host, path, parameters and body</li>
<li>a timestamp and a unique nonce to block replay attacks</li>
<li>the consumer key to identify the caller, and</li>
<li>the consumer secret to verify the identity of the caller</li>
</ul>

<p><span class=\"color\"><a href=\"http://tools.ietf.org/html/rfc5849#section-3.4\">Section 3.4</a></span> of the OAuth specification describes in detail how to sign requests.</p>

</p><p>To request a consumer key and secret, drop us a line at <a href='mailto:api@tuneefy.com' class='color'>api@tuneefy.com</a>.</p>";
$lang['api_platforms_title'] = "Available platforms and platforms ids";
$lang['api_platforms'] = "<p>tuneefy actually supports at most <span class=\"color\">%d</span> platforms (or music services) for search, lookup and/or aggregation.</p><p>Each platform has a unique id that is identified below, along with the supported methods :</p>";
$lang['api_methods_title'] = "Available methods";
$lang['api_methods'] = "The following methods can be used on tuneefy : <a href=\"#lookup\"><span class=\"color\">lookup</span></a>, <a href=\"#search\"><span class=\"color\">search</span></a> and <a href=\"#aggregate\"><span class=\"color\">aggregate</span></a>.";

$lang['api_arguments'] = "Arguments";
$lang['api_returns'] = "Returns";
$lang['api_integer'] = "integer";
$lang['api_string'] = "string";
$lang['api_object'] = "object";
$lang['api_ex_call'] = "Example Call";
$lang['api_ex_response'] = "Example response";
$lang['api_required'] = "required";
$lang['api_optional'] = "optional";

$lang['api_platforms_platform'] = "Platform";
$lang['api_platforms_search_tracks'] = "search / agg. (tracks)";
$lang['api_platforms_search_albums'] = "search / agg. (album)";
$lang['api_platforms_lookup'] = "lookup (tracks)";

$lang['api_query_terms'] = "the query terms (url-encoded)";
$lang['api_max_results'] = "the maximum number of results returned (0 - 100)";
$lang['api_alt'] = "the alternative response format ('json' or 'xml')";
$lang['api_platform_search'] = "the platform id on which to search";
$lang['api_type_search'] = "the type of search (track or album)";

$lang['api_lookedup_platform'] = "the platform of the permalink, or -1 if a basic search is requested";
$lang['api_query_cleaned'] = "the cleaned query";
$lang['api_lookedup_item'] = "an object containing the track, if found, null otherwise. The object properties are <span class=\"color\">name</span> (the title of the song), <span class=\"color\">artist</span> (the interpreter), <span class=\"color\">album</span> (one album where the song is, if found), <span class=\"color\">picture</span> (an image for this track - usually the cover of the album, if found) and <span class=\"color\">link</span> (the link to the song on the platform from which the permalink was coming - usually matches the given permalink).";

$lang['api_lookup_description'] = "This method returns a track object corresponding to the permalink requested, if found.";

$lang['api_search_description'] = "This method searches for a track or album, for one or more terms, on the specified platform.";
$lang['api_track_results'] = "an object containing the tracks or albums, if found, null otherwise. The object properties are <span class=\"color\">name</span> (the title of the song or null in the case of an album search), <span class=\"color\">artist</span> (the interpreter), <span class=\"color\">album</span> (one album where the song is, if found, in the case of a track search, or the album searched in the case of an album search), <span class=\"color\">picture</span> (an image for this track - usually the cover of the album, if found), <span class=\"color\">link</span> (the link to the song or album on the platform from which the permalink was coming - usually matches the given permalink) and the <span class=\"color\">score</span> (1: most relevant match).";

$lang['api_aggregate_description'] = "This method aggregates searches for a track or album, for one or more terms, on all the possible platforms for the type of search.";

$lang['api_disclaimer_title'] = "Disclaimer";
$lang['api_disclaimer'] = "The tuneefy API relies on various APIs, and is thus highly dependent on their availability, health, return codes, timeout, will to respond, apathy, etc ..";

// Search
$lang['query_label']= "Search for a track, album or paste a link here"; 
$lang['available_platforms'] = "Broaden your search to more services";
$lang['results_found'] = "We found #nb# #type# for your search &laquo; #iq# &raquo;"; 
$lang['result_found_widget'] = "Is that the track you're looking for ?";
$lang['merge_label']   = "Merge tracks even if the album is not the same"; 
$lang['search_button'] = "Search"; 
$lang['search_alert']  = "#p# did not return any result.";
$lang['invalid_query'] = "Your query seems invalid. Please try with a different phrase.";
$lang['api_alert'] = "#p# API did not respond. It may be down, please try again in a few seconds.";
$lang['no_result'] = "Wooops ... no result found !";
$lang['_tracks'] = "tracks";
$lang['_albums'] = "albums";
$lang['newImage'] = "new.png";
$lang['newAlt'] = "new!";
$lang['moreOptionsImage'] = "more_options.png";
$lang['moreOptionsAlt'] = "More options!";


$lang['help_text_close'] = "Don't ever show me this message again";
$lang['help_text_title'] = "Hey ! What about trying searches like that :";
$lang['help_text_more'] = "and there are many more patterns ! ";
$lang['help_text_more_button'] = "see here";
$lang['help_text'] = "<div class=\"example\"><span class=\"searchForIt\">http://www.deezer.com/listen-10236179</span><span class=\"searchForWhat\">A track on Deezer</span></div>
<div class=\"example\"><span class=\"searchForIt\">http://www.lastfm.fr/music/Caribou/_/Odessa</span><span class=\"searchForWhat\">A song on Last.fm</span></div>
<div class=\"example\"><span class=\"searchForIt\">spotify:track:5jhJur5n4fasblLSCOcrTp</span><span class=\"searchForWhat\">A track on Spotify</span></div>";

$lang['yes'] = "Yes";
$lang['no'] = "No";


// Results
$lang['various_albums'] = "(Various Albums)";
$lang['share']       = "Share";
$lang['share_tip']   = "Get the link to share '#name#' on all these platforms!";
$lang['listen_to']   = "Listen to '#name#' on '#p#'";
$lang['featuring']   = "feat. ";
$lang['album_cover'] = "Album cover";
$lang['header_track']     = "Track";
$lang['header_artist']    = "Artist";
$lang['header_album']     = "Album";
$lang['header_available'] = "Available on";

$lang['back_to_top'] = "Back To Top";

// Widget
$lang['see_more']= "More results"; 

// Pick of the day
$lang['pick_discover']= "Discover it !";
$lang['pick_of_the_day']= "Our pick for today";
$lang['last_track_shared']= "Last share";
$lang['most_viewed_this_week']= "Most viewed this week";

// Info
$lang['info_welcome']= "All for Music, Music for all !"; 
$lang['info_content']= "Let's say you use <span class=\"color\">Spotify</span> and your friends use other music platforms such as <span class=\"color\">Deezer</span>, <span class=\"color\">Soundcloud</span> or <span class=\"color\">Grooveshark</span> for instance. When it comes to sharing music, what a mess!<br/><span class=\"color\">tuneefy</span> bridges the gap — search for a track or paste the corresponding link from your platform, hit 'Share' for the tune you're looking for and <span class=\"color\">voila</span> !";
$lang['more_info']   = "Tell Me More !"; 


// Footer
$lang['about_us']= "About Us"; 
$lang['contact']= "Contact";
$lang['follow_twitter']= "Follow us on Twitter";
$lang['copyright']= "tuneefy &copy; 2011 - 2012"; 
$lang['endorsement']= "This product uses Spotify (resp. Deezer, Grooveshark, Last.fm, Soundcloud, HypeMachine, Youtube, Mixcloud, Rdio, iTunes, Qobuz) APIs but is not endorsed, certified or otherwise approved in any way by Spotify (resp. Deezer, Grooveshark, Last.fm, Soundcloud, HypeMachine, Youtube, Mixcloud, Rdio, iTunes, Qobuz). Each brand and name is or may be the registered trade mark of its respective owner.";

// Share
$lang['track_intro']= "Let's turn up the volume and listen to some music."; 
$lang['track_listen_to']   = "You can listen to this track on these sites : "; 
$lang['track_share']   = "Share this track : "; 
$lang['track_facebook']   = "Share this track on Facebook"; 
$lang['track_embed']   = "Embed a widget of this track"; 
$lang['track_twitter']   = "Share this track on Twitter";  
$lang['track_mail']   = "Send the link to this track via mail"; 
$lang['track_mail_subject']   = "Someone wants to share the track '%s' (by '%s') with you"; 
$lang['track_mail_body']   = "Click here to discover this track : %s"; 
$lang['track_facebook_action']   = "Share music too !";
$lang['track_twitter_status']   = "Listen to '%s' by '%s'  %s %%23tuneefy";
$lang['track_seeOnTuneefy']   = "Go to the tuneefy page for this track";
$lang['track_title']= "Listen to %s by %s";
$lang['track_description']= "Get the links to listen to %s by %s on tuneefy, a new unified way to share music over various online music services !";

// Album
$lang['album_intro']= "Let's turn up the volume and listen to this album."; 
$lang['album_listen_to']   = "You can listen to this album on these sites : "; 
$lang['album_share']   = "Share this album : "; 
$lang['album_facebook']   = "Share this album on Facebook"; 
$lang['album_embed']   = "Embed a widget of this album"; 
$lang['album_twitter']   = "Share this album on Twitter";  
$lang['album_mail']   = "Send the link to this album via mail"; 
$lang['album_mail_subject']   = "Someone wants to share the album '%s' (by '%s') with you"; 
$lang['album_mail_body']   = "Click here to discover this album : %s"; 
$lang['album_facebook_action']   = "Share music too !";
$lang['album_twitter_status']   = "Listen to the album '%s' by '%s'  %s %%23tuneefy";
$lang['album_seeOnTuneefy']   = "Go to the tuneefy page for this album";
$lang['album_title']= "Listen to the album %s by %s";
$lang['album_description']= "Get the links to listen to the album %s by %s on tuneefy, a new unified way to share music over various online music services !";

// Stats
$lang['stats_title_long']= "What people are sharing lately";
$lang['global_stats'] = "Music services you are using"; 
$lang['most_viewed_tracks'] = "%d Most Viewed Tracks"; 
$lang['most_viewed_albums'] = "%d Most Viewed Albums"; 
$lang['most_viewed_artists']= "%d Most Viewed Artists"; 
$lang['total_tracks_viewed'] = "Total Tracks Viewed :"; 
$lang['total_links_clicked']= "Total Platforms Links Clicked :"; 
$lang['views']= "<span class=\"color\">%d</span> views"; 

// About
$lang['about_title_long']= "About tuneefy, the Universe, and Everything"; 
$lang['the_team']= "About the Team"; 
$lang['contact_us']= "Contact Us"; 
$lang['contact_us_email']= "Your email"; 
$lang['contact_us_message']= "Your message"; 
$lang['contact_us_send']= "Send"; 
$lang['facts_info'] = "<h3>Sharing Music Online.<br />Made easy</h3>
      <p>Tuneefy unifies various online music streaming services to provide you with a fast and easy way to search for your favorite tunes and share them around you.
      </p>";
$lang['facts_friends'] = "<h3>Keep your friends</h3>
      <p>Tuneefy is genuine altruism; your friends get directly to your music, whatever the platform. No-brainer.<br /><span class=\"color\">Hell, yeah</span>.
      </p>";
$lang['facts_pertinence'] = "<h3>Pertinence is key</h3>
      <p>Tracks are sorted with sharing in mind. You want to address a wide range of services, so the top tracks are the one present on the most platforms.
      </p>";
$lang['facts_free'] = "<h3>Free for you, Music Lovers !</h3>
      <p>Tuneefy is free and ad-free. We care about music, not advertising or revenue.
      </p>";
$lang['facts_supported'] = "<a name=\"patterns\"></a><h3>Supported link patterns</h3>
      <p>Of course you can search for any keywords, but there is more !<br />Have a look at all the different URLs & links we support !
      </p>";
$lang['facts_supported_list'] = "<ul class=\"platformsPatterns\">
        <li class=\"platform active\">Deezer
        <ul>
          <li class=\"song\">Song link<br/><span class=\"link\">http://www.deezer.com/listen-10236179</span></li>
          <li class=\"artist\">Artist link<br/><span class=\"link\">http://www.deezer.com/fr/music/radiohead</span></li>
          <li class=\"album\">Album link<br/><span class=\"link\">http://www.deezer.com/fr/music/rjd2/deadringer-144183</span></li>
        </ul>
        </li>
        <li class=\"platform\">Spotify
        <ul style=\"display: none;\">
          <li class=\"song\">Song link<br/><span class=\"link\">spotify:track:6GOxgLKnfd567oG2VpfJio</span></li>
          <li class=\"artist\">Artist link<br/><span class=\"link\">http://open.spotify.com/artist/6UUrUCIZtQeOf8tC0WuzRy</span></li>
          <li class=\"album\">Album link<br/><span class=\"link\">spotify:album:2bRcCP8NYDgO7gtRbkcqdk</span></li>
        </ul>
        </li>
        <li class=\"platform\">Last.Fm
        <ul style=\"display: none;\">
          <li class=\"song\">Song link<br/><span class=\"link\">http://lastfm.fr/music/Muse/_/Bliss</span></li>
          <li class=\"artist\">Artist link<br/><span class=\"link\">http://www.lastfm.fr/music/Sex+Pistols</span></li>
          <li class=\"album\">Album link<br/><span class=\"link\">http://www.last.fm/music/The+Clash/London+Calling</span></li>
          </ul>
        </li>
        <li class=\"platform\">Grooveshark
        <ul style=\"display: none;\">
          <li class=\"song\">Song link<br/><span class=\"link\">http://grooveshark.com/s/Blizzard/2r6qUb?src=5</span></li>
          <li class=\"artist\">Artist link<br/><span class=\"link\">http://grooveshark.com/artist/Rone/76915</span></li>
          <li class=\"album\">Album link<br/><span class=\"link\">http://grooveshark.com/album/30/1133717</span></li>
        </ul>
        </li>
        <li class=\"platform\">And more ...
        <ul style=\"display: none;\">
          <li class=\"song\">Soundcloud link<br/><span class=\"link\">http://soundcloud.com/hsz/debich</span></li>
          <li class=\"artist\">HypeMachine link<br/><span class=\"link\">http://hypem.com/item/1g079/</span></li>
          <li class=\"album\">Youtube music link<br/><span class=\"link\">http://www.youtube.com/watch?v=_FOyHhU0i7k</span></li>
        </ul>
        </li>
        <li class=\"platform\">... and more !
        <ul style=\"display: none;\">
          <li class=\"song\">All Rdio links<br/><span class=\"link\">http://www.rdio.com/#/artist/Crash_Test_Dummies</span></li>
          <li class=\"artist\">Qobuz<br/><span class=\"link\">http://player.qobuz.com/#!/track/5280111</span></li>
          <li class=\"album\">Want to suggest a link ?<br/><span class=\"link\">contact us !</span></li>
        </ul>
        </li>
        </ul>";
$lang['facts_picks'] = "<h3>We love Music, too</h3>
      <p>Everyday, find a new staff pick on the home page, and spread the tune...
      </p>";
$lang['facts_team_idea'] = "<p class=\"realName\">tchap</p>
      <p class=\"achievement\">Idea, development, code poetry</p>";
$lang['facts_team_design'] = "<p class=\"realName\">_W___</p>
      <p class=\"achievement\">Design, animated GIFs, painting</p>";
$lang['facts_minify'] = "<h3>There's a widget for that</h3>
      <p>Drag the button below to your bookmark bar and unleash the power of tuneefy : <a href=\"%s\" class=\"widgetBookmark\"><img src=\""._SITE_URL."/img/widget_button.png\" alt=\"tuneefy it!\" width=\"93\" height=\"33\"/></a>
      </p>";
$lang['sending_mail'] = "Sending mail ...";
$lang['success_mail'] = "Thanks for your message !<br />We'll get back to you as soon as we wake up.";
$lang['error_mail'] = "There has been an error sending your mail.<br />Please <a href=\""._SITE_URL."/about\" class=\"color\">try again</a>.";

// Playlists
$lang['playlists_title'] = "Playlists converter";
$lang['playlists_login_label'] = "Choose the platform on which you want to import your playlist :";
$lang['playlists_login_button'] = "Log in";
$lang['playlists_cancel_button'] = "Cancel";
$lang['playlists_logged_label'] = "You are now logged on your music service.";
$lang['playlists_logout_button'] = "Log out";
$lang['playlists_query_label'] = "Paste the playlist url here ...";
$lang['playlists_button'] = "Convert";
$lang['playlists_results_found'] = "Processing #nb# #type# in the playlist &laquo; #iq# &raquo; ...";

// Error messages
$lang['error_503_title'] = "Lookin' for somethin' ?";
$lang['error_503'] = "A kitten army is on the way to your home now.";
$lang['error_404_title'] = "Couldn't find this page";
$lang['error_404'] = "404. Looks like you broke the Internet.<br/>We're pretty sure you typed gibberish in the url bar, you fool.<br />(Or it may just be a dead link. Maybe.)";
$lang['error_woops_title'] = "Wooooooooops";
$lang['error_woops'] = "500. This occurs when our code blows up or when the server is dying.<br />Chuck Norris is on site right now. We'll keep you updated soon.";
$lang['error_ie_title'] = "Ouch.";
$lang['error_ie'] = "The browser you're using to access tuneefy seems a bit too old for you to get the full experience of the site... You might be in a locked-down corporate environment.";
$lang['error_ie_action'] = "If you want to be able to surf the modern web smoothly, we recommend that you get a newer version of Internet Explorer or even another browser. Here is a list of the most popular modern web browsers:";

$lang['error_ie_get'] = "Get %s";

$lang['error_action'] = "Go back home";

// Coming soon
$lang['coming_soon_submit'] = "Notify me !";
$lang['coming_soon_title']  = "tuneefy is coming ...";
$lang['coming_soon_label']  = "Enter your e-mail address ...";
$lang['coming_soon_thanks'] = "Thank you. We will send you an e-mail when Tuneefy is ready.";
$lang['coming_soon_oops']   = "Oooops. There has been an error saving your mail. Have you checked it is correct ?";
$lang['coming_soon_twitter']= "Follow us on Twitter";
$lang['coming_soon_tagline']= $lang['tagline'] . " Coming soon - Follow <a href=\"http://www.twitter.com/tuneefy\"><span class=\"color\">@tuneefy</span></a> !<span class=\"tip\">tuneefy will soon help you share music links regardless of the online streaming platform or service you and your friends use.</span>"; 
$lang['coming_soon_blog']   = "Yes we have a blog";
$lang['coming_soon_info']   = "tuneefy will be online soon. Give us your e-mail address so we can notify you when it goes live !";

$lang['coming_soon_disclaimer']= "Be assured. We are very careful about privacy - We will not disclose your email to any other parties, even if they offer us dounughts n' stuff, u know.";
