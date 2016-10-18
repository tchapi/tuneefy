<?php

// Header
$lang['general_title']= "tuneefy";
$lang['description']= "tuneefy est un nouveau service permettant de partager de la musique avec vos amis indépendamment de leur plateforme d'écoute! ";
$lang['tagline']= "Partager de la <span class='color'>musique</span>. <span class='color'>Facilement</span>.";
$lang['tags']= "écouter, partager, musique, online, unifier, plateforme, streaming, ami, tuneefy, chansons, partage";
$lang['search_title']= "Votre recherche pour %s";

// Github
$lang['github']= "Je suis sur GitHub";

// Menu
$lang['home_title']= "Accueil";
$lang['home_tip']= "C'est là que ça se passe !";
$lang['stats_title']= "Tendances";
$lang['stats_tip']= "Des stats, des stats ..";
$lang['about_title']= "À propos";
$lang['about_tip']= "C'est quoi, ce truc ?";

// API
$lang['api_title']="API";
$lang['api_intro']="Documentation de l'API Rest tuneefy (beta)";
$lang['api_overview_title'] = "Généralités";

$lang['api_overview'] = "<p>L'API tuneefy est une API RESTful vous permettant de rechercher des pistes et albums, traduire des permaliens et aggréger des résultats.</p><p>L'API peut renvoyer ses résultats sous deux formats : </p><ul><li><span class=\"color\">JSON</span></li><li><span class=\"color\">XML</span></li></ul><p>Le serveur vérifiera d'abord la présence d'un header '<span class=\"color\">HTTP_ACCEPT</span>' dans la requête, contenant un des MIME-types correspondants ci-dessus. Le type de retour peut être forcé grace au paramètre 'alt' disponible pour toutes les methodes détaillées ci-dessous. Par défaut, l'API retournera de l'XML.</p><p>Toutes les réponses sont encodées en <span class=\"color\">UTF-8</span>, toutes les requêtes doivent également l'être.</p>";
$lang['api_endpoint_title'] = "URL de base de l'API";
$lang['api_auth_title'] = "Authentification";
$lang['api_auth'] = "<p>Chaque requête à l'API doit être signée suivant le protocole <span class=\"color\">2-legged <a href='http://oauth.net/documentation/getting-started/' target='_blank'>OAuth</a></span>.</p><p>Demandez dès aujourd'hui votre clé publique et privée en nous envoyant un mail : <a href='mailto:api@tuneefy.com' class='color'>api@tuneefy.com</a>.</p>";
$lang['api_platforms_title'] = "Plateformes disponibles via l'API et leur id correspondant";
$lang['api_platforms'] = "<p>tuneefy supporte actuellement <span class=\"color\">%d</span> plateformes (ou sites musicaux) pour la recherche, la traduction et l'aggregation.</p><p>Chaque plateforme a un id unique qui est détaillé ci-dessous, avec les méthodes disponibles pour chaque plateforme :</p>";
$lang['api_methods_title'] = "Méthodes disponibles";
$lang['api_methods'] = "Les méthodes suivantes sont disponibles : <a href=\"#lookup\"><span class=\"color\">lookup</span></a>, <a href=\"#search\"><span class=\"color\">search</span></a> et <a href=\"#aggregate\"><span class=\"color\">aggregate</span></a>.";

$lang['api_problems_title'] = "Des bugs ? Des questions ?";
$lang['api_problems'] = "<p>Si vous trouvez un bug, si vous avez une suggestion ou une critique, une amélioration à proposer, n'hésitez pas ! Envoyez-nous un petit email à <a href='mailto:team@tuneefy.com' class='color'>team@tuneefy.com</a></p>";

$lang['api_arguments'] = "Paramètres";
$lang['api_warning'] = "Attention";
$lang['api_returns'] = "Retour";
$lang['api_integer'] = "entier";
$lang['api_string'] = "chaîne";
$lang['api_object'] = "objet";
$lang['api_ex_call'] = "Exemple d'appel à l'API";
$lang['api_ex_response'] = "Exemple de réponse";
$lang['api_required'] = "obligatoire";
$lang['api_optional'] = "optionnel";

$lang['api_platforms_platform'] = "Plateforme";
$lang['api_platforms_search_tracks'] = "search / agg. (pistes)";
$lang['api_platforms_search_albums'] = "search / agg. (album)";
$lang['api_platforms_lookup'] = "lookup (pistes)";

$lang['api_query_terms'] = "les termes recherchés (url-encodés)";
$lang['api_max_results'] = "nombre maximum de résultats (0 - 100)";
$lang['api_alt'] = "format de réponse alternatif ('json' ou 'xml')";
$lang['api_platform_search'] = "l'id de la plateforme sur laquelle chercher";
$lang['api_platforms_aggregate'] = "les ids des plateformes sur lesquelles chercher (séparées par des virgules)";
$lang['api_type_search'] = "le type d'objet recherché (track (piste) ou album)";

$lang['api_lookedup_platform'] = "la plateforme du permalien, ou -1 si la recherche est standard";
$lang['api_query_cleaned'] = "la recherche nettoyée";
$lang['api_lookedup_item'] = "un objet contenant la piste, si trouvée, ou null sinon. Les propriétés de cet objet sont : <span class=\"color\">name</span> (le titre de la piste), <span class=\"color\">artist</span> (l'interprète), <span class=\"color\">album</span> (un album contenant la piste), <span class=\"color\">picture</span> (une image illustrant la piste, généralement la pochette d'album) et <span class=\"color\">link</span> (le lien vers la piste sur la plateforme identifiée (dans le cas d'un permalien)).";

$lang['api_lookup_description'] = "Cette méthode retourne un objet piste correspondant au permalien recherché.";

$lang['api_search_description'] = "Cette méthode recherche une piste ou un album avec les termes fournis, sur la plateforme spécifiée.";
$lang['api_track_results'] = "un objet contenant les pistes ou albums, ou null. Les propriétés de chaque objet sont : <span class=\"color\">name</span> (le titre de la piste ou null dans le cas d'un album), <span class=\"color\">artist</span> (l'interprète), <span class=\"color\">album</span> (un album sur lequel se trouve la piste dans le cas d'une piste, l'album sinon), <span class=\"color\">picture</span> (une illustration pour la piste ou l'album - généralement la pochette de l'album correspondant), <span class=\"color\">link</span> (le lien vers la piste sur la plateforme identifiée) et <span class=\"color\">score</span> (score plus grand <=> résultat plus pertinent).";

$lang['api_aggregate_description'] = "Cette méthode aggrège les résultats pour une piste ou un album pour les termes recherchés, sur toutes les plateformes possibles pour ce type de recherche.";

$lang['api_aggregate_warning'] ="<span class=\"color\">Cette méthode est leeeeeeeeente car elle s'appuie sur des appels synchrones à des API tierces. Cela va changer et s'améliorer rapidement, mais vous ne pourrez pas dire qu'on ne vous a pas prévenus !</span>";

$lang['api_disclaimer_title'] = "Précautions d'usage";
$lang['api_disclaimer'] = "<strong>L'API est en cours de développement (màj Janvier 2013).</strong> Cette documentation ne va pas varier des masses mais il se peut que les performances de l'API, certains détails des appels de méthodes ou des retours changent de temps en temps alors que nous travaillons dessus. L'API s'appuie fortement sur des API tierces dont les temps de réponses et les performances de manière générale ne sont pas garanties, ce qui peut dégrader le résultats que renvoie l'API tuneefy.<br/><br/>La méthode aggregate notamment, est très lente pour le moment. Nous travaillons à la rendre plus rapide à l'instant même où vous lisez ces lignes.";


// Search
$lang['query_label']= "Cherchez une chanson, un album, .. ou collez un lien";
$lang['available_platforms'] = "Rechercher sur ces services";
$lang['results_found'] = "Nous avons trouvé #nb# #type# pour &laquo; #iq# &raquo;";
$lang['result_found_widget'] = "Ça devrait être ce que vous cherchez ..";
$lang['merge_label']   = "Grouper les titres identiques venant d'albums divers";
$lang['search_button'] = "Chercher";
$lang['search_alert']  = "Aucun résultat sur #p#.";
$lang['invalid_query'] = "Votre recherche a l'air invalide. Veuillez réessayer avec des mots-clés ou un permalien différent.";
$lang['api_alert'] = "L'API de #p# n'a pas répondu. Elle est peut-être inaccessible pour le moment, veuillez réessayer dans quelques instants.";
$lang['no_result'] = "Oups ... pas de résultats !";
$lang['_tracks'] = "chansons";
$lang['_albums'] = "albums";
$lang['newImage'] = "nouveau.png";
$lang['newAlt'] = "nouveau!";
$lang['moreOptionsImage'] = "plus_options.png";
$lang['moreOptionsAlt'] = "Plus d'options!";

$lang['help_text_close'] = "Ne plus afficher ce message";
$lang['help_text_title'] = "Salut ! Quelques idées de recherche :";
$lang['help_text_more'] = "et il y a plein d'autres liens supportés ! ";
$lang['help_text_more_button'] = "je veux voir";
$lang['help_text'] = "<div class=\"example\"><span class=\"searchForIt\">http://www.deezer.com/listen-10236179</span><span class=\"searchForWhat\">Une chanson sur Deezer</span></div>
<div class=\"example\"><span class=\"searchForIt\">http://www.lastfm.fr/music/Caribou/_/Odessa</span><span class=\"searchForWhat\">Une sur Last.fm</span></div>
<div class=\"example\"><span class=\"searchForIt\">spotify:track:5jhJur5n4fasblLSCOcrTp</span><span class=\"searchForWhat\">Et une sur Spotify</span></div>";

$lang['yes'] = "Oui";
$lang['no'] = "Non";

// Results
$lang['various_albums'] = "(Plusieurs albums)";
$lang['share']       = "Partager";
$lang['share_tip']   = "Obtenez tous les liens pour partager '#name#'!";
$lang['listen_to']   = "Écoutez '#name#' sur '#p#'";
$lang['featuring']   = "feat. ";
$lang['album_cover'] = "Pochette";
$lang['header_track']     = "Chanson";
$lang['header_artist']    = "Artiste";
$lang['header_album']     = "Album";
$lang['header_available'] = "Disponible sur";

$lang['back_to_top'] = "Retour en haut";

// Widget
$lang['see_more']= "en voir plus";

// Pick of the day
$lang['pick_discover']= "Je découvre";
$lang['pick_of_the_day']= "Notre sélection du jour";
$lang['last_track_shared']= "Dernier partage";
$lang['most_viewed_this_week']= "La plus vue cette semaine";

// Info
$lang['info_welcome']= "De la Musique pour tous, tous pour la Musique !";
$lang['info_content']= "Imaginons. Vous utilisez <span class=\"color\">Spotify</span> et vos amis d'autres services, comme <span class=\"color\">Deezer</span>, <span class=\"color\">Soundcloud</span> ou <span class=\"color\">Grooveshark</span> par exemple. Quand vous voulez leur partager de la musique ... c'est la guerre!<br/><span class=\"color\">tuneefy</span> est le chaînon manquant — recherchez une chanson, un artiste ou album, ou collez un lien issu d'un service de streaming, 'Partagez' la chanson cherchée et <span class=\"color\">voila</span> ! Tout le monde vous aime ! <span class=\"color\">Trop facile</span>.";
$lang['more_info']   = "Plus d'infos !";


// Footer
$lang['about_us']= "À Propos";
$lang['contact']= "Contact";
$lang['follow_twitter']= "Nous suivre sur Twitter";
$lang['copyright']= "tuneefy &copy; 2011 - 2015";
$lang['endorsement']= "Ce service utilise les API de Spotify (resp. Deezer, Grooveshark, Last.fm, Soundcloud, HypeMachine, Youtube, Mixcloud, Rdio, iTunes, Qobuz, Beats Music) mais n'est pas approuvé ni certifié de quelque manière que ce soit par Spotify (resp. Deezer, Grooveshark, Last.fm, Soundcloud, HypeMachine, Youtube, Mixcloud, Rdio, iTunes, Qobuz, Beats Music). Les noms, marques et logos cités ou utilisés sur ce site sont la propriété de leurs déposants respectifs.";

// Share
$lang['track_intro']= "Allez on monte le son, en avant la musique !";
$lang['track_listen_to']   = "Cette chanson est disponible sur ces sites : ";
$lang['track_share']   = "Partager cette chanson : ";
$lang['track_facebook']   = "Partager sur Facebook";
$lang['track_embed']   = "Publiez cette chanson sur votre site";
$lang['track_twitter']   = "Partager sur Twitter";
$lang['track_mail']   = "Envoyer le lien par mail";
$lang['track_mail_subject']   = "Un ami veut partager la chanson '%s' (de '%s') avec toi :";
$lang['track_mail_body']   = "Clique ici pour la découvrir : %s";
$lang['track_facebook_action']   = "Partagez !";
$lang['track_twitter_status']   = "Écoutez '%s', par '%s'  %s %%23tuneefy";
$lang['track_seeOnTuneefy']   = "Voir la page tuneefy de cette chanson";
$lang['track_title']= "Écoutez %s, par %s";
$lang['track_description']= "Tous les liens pour écouter %s par %s grâce à tuneefy, un service de partage de musique indépendant de la plateforme d'écoute !";

// Album
$lang['album_intro']= "Allez on monte le son, en avant la musique !";
$lang['album_listen_to']   = "Cet album est disponible sur ces sites : ";
$lang['album_share']   = "Partager cet album : ";
$lang['album_facebook']   = "Partager cet album sur Facebook";
$lang['album_embed']   = "Publiez cet album sur votre site";
$lang['album_twitter']   = "Partager cet album sur Twitter";
$lang['album_mail']   = "Envoyer le lien par mail";
$lang['album_mail_subject']   = "Un ami veut partager l'album '%s' (de '%s') avec toi :";
$lang['album_mail_body']   = "Clique ici pour le découvrir : %s";
$lang['album_facebook_action']   = "Partagez !";
$lang['album_twitter_status']   = "Écoutez l'album '%s', par '%s'  %s %%23tuneefy";
$lang['album_seeOnTuneefy']   = "Voir la page tuneefy de cet album";
$lang['album_title']= "Écoutez l'album %s, par %s";
$lang['album_description']= "Tous les liens pour écouter l'album %s par %s grâce à tuneefy, un service de partage de musique indépendant de la plateforme d'écoute !";

// Stats
$lang['stats_title_long']= "Les tendances de partage";
$lang['global_stats'] = "Les sites de musique que vous utilisez";
$lang['most_viewed_tracks'] = "Les %d Chansons les plus vues";
$lang['most_viewed_albums'] = "Les %d Albums les plus vus";
$lang['most_viewed_artists']= "Les %d Artistes les plus vus";
$lang['total_tracks_viewed'] = "Nombre total de vues :";
$lang['total_links_clicked']= "Nombre total de liens cliqués :";
$lang['views']= "<span class=\"color\">%d</span> vues";

// About
$lang['about_title_long']= "la Vie, tuneefy, l'Univers et le Reste";
$lang['the_team']= "L'Équipe";
$lang['contact_us']= "Nous Contacter";
$lang['contact_us_email']= "Votre email";
$lang['contact_us_message']= "Votre message";
$lang['contact_us_send']= "Envoyer";
$lang['facts_info'] = "<h3>Partager de la musique.<br />Mais en plus simple.</h3>
      <p>Tuneefy regroupe les différentes plateformes de streaming musical en vous donnant un lien unique proposant tous les liens issus de ces plateformes.
      </p>";
$lang['facts_friends'] = "<h3>Chouchoutez vos amis</h3>
      <p>Tuneefy, c'est de l'altruisme à l'état pur; vos amis obtiennent directement le lien musical sur leur plateforme de prédilection. Du coup, ils vous aiment encore plus. <span class=\"color\">Et ça, c'est cool</span>.
      </p>";
$lang['facts_pertinence'] = "<h3>Recherche pertinente</h3>
      <p>Les résultats sont triés intelligemment. Vous voulez toujours partager au plus grand nombre : les premiers résultats sont ceux qui sont présents sur le plus grand nombre de plateformes.
      </p>";
$lang['facts_free'] = "<h3>C'est moins cher<br />qu'un forfait à 2€</h3>
      <p>Tuneefy est gratuit et sans pollution publicitaire. Parce que ce qui nous intéresse, c'est la musique. Point.
      </p>";
$lang['facts_supported'] = "<a name=\"patterns\"></a><h3>Types de liens supportés</h3>
      <p>Bien sûr vous pouvez rechercher n'importe quel mot-clé, mais encore mieux, vous pouvez copier-coller les liens de quasiment toutes vos plateformes favorites ! Jetez un oeil à droite ..
      </p>";
$lang['facts_supported_list'] = "<ul class=\"platformsPatterns\">
        <li class=\"platform active\">Deezer
        <ul>
          <li class=\"song\">Chanson<br/><span class=\"link\">http://www.deezer.com/listen-10236179</span></li>
          <li class=\"artist\">Artiste<br/><span class=\"link\">http://www.deezer.com/fr/music/radiohead</span></li>
          <li class=\"album\">Album<br/><span class=\"link\">http://www.deezer.com/fr/music/rjd2/deadringer-144183</span></li>
        </ul>
        </li>
        <li class=\"platform\">Spotify
        <ul style=\"display: none;\">
          <li class=\"song\">Chanson<br/><span class=\"link\">spotify:track:6GOxgLKnfd567oG2VpfJio</span></li>
          <li class=\"artist\">Artiste<br/><span class=\"link\">http://play.spotify.com/artist/6UUrUCIZtQeOf8tC0WuzRy</span></li>
          <li class=\"album\">Album<br/><span class=\"link\">spotify:album:2bRcCP8NYDgO7gtRbkcqdk</span></li>
        </ul>
        </li>
        <li class=\"platform\">Last.Fm
        <ul style=\"display: none;\">
          <li class=\"song\">Chanson<br/><span class=\"link\">http://lastfm.fr/music/Muse/_/Bliss</span></li>
          <li class=\"artist\">Artiste<br/><span class=\"link\">http://www.lastfm.fr/music/Sex+Pistols</span></li>
          <li class=\"album\">Album<br/><span class=\"link\">http://www.last.fm/music/The+Clash/London+Calling</span></li>
          </ul>
        </li>
        <li class=\"platform\">Grooveshark
        <ul style=\"display: none;\">
          <li class=\"song\">Chanson<br/><span class=\"link\">http://grooveshark.com/s/Blizzard/2r6qUb?src=5</span></li>
          <li class=\"artist\">Artiste<br/><span class=\"link\">http://grooveshark.com/artist/Rone/76915</span></li>
          <li class=\"album\">Album<br/><span class=\"link\">http://grooveshark.com/album/30/1133717</span></li>
        </ul>
        </li>
        <li class=\"platform\">Et encore ...
        <ul style=\"display: none;\">
          <li class=\"song\">Soundcloud<br/><span class=\"link\">http://soundcloud.com/hsz/debich</span></li>
          <li class=\"artist\">HypeMachine<br/><span class=\"link\">http://hypem.com/item/1g079/</span></li>
          <li class=\"album\">Youtube (musique)<br/><span class=\"link\">http://www.youtube.com/watch?v=_FOyHhU0i7k</span></li>
        </ul>
        </li>
        <li class=\"platform\">Toujours plus ...
        <ul style=\"display: none;\">
          <li class=\"song\">Rdio<br/><span class=\"link\">http://www.rdio.com/#/artist/Crash_Test_Dummies</span></li>
          <li class=\"artist\">Qobuz<br/><span class=\"link\">http://player.qobuz.com/#!/track/5280111</span></li>
          <li class=\"album\">Une suggestion ?<br/><span class=\"link\">contactez-nous !</span></li>
        </ul>
        </li>
        </ul>";
$lang['facts_picks'] = "<h3>Nous aussi on adore la musique</h3>
      <p>Alors tous les jours on vous propose un titre à découvrir, à écouter, à partager ...
      </p>";
$lang['facts_team_idea'] = "<p class=\"realName\">tchap</p>
      <p class=\"achievement\">Idée, pintes de café, jPoésie</p>";
$lang['facts_team_design'] = "<p class=\"realName\">_W___</p>
      <p class=\"achievement\">Design, gribouillages, GIFs animés</p>";
$lang['facts_minify'] = "<h3>Il y a un bookmarklet pour ça</h3>
      <p>Glissez le bookmarklet ci-dessous dans votre barre de favoris et un monde de partage s'ouvrira à vous (on rigole pas) : <a href=\"%s\" class=\"widgetBookmark\"><img src=\""._SITE_URL."/img/widget_button.png\" alt=\"tuneefy it!\" width=\"93\" height=\"33\"/></a>
      </p>";
$lang['sending_mail'] = "Envoi du mail ...";
$lang['success_mail'] = "Merci pour votre message !<br />On vous répond dès qu'on se réveille.";
$lang['error_mail'] = "Il y a eu un petit problème lors de l'envoi du mail.<br /><a href=\""._SITE_URL."/about\" class=\"color\">Réessayez</a> s'il vous plaît.";

// Playlists
$lang['playlists_title'] = "Convertisseur de playlists";
$lang['playlists_login_label'] = "Choisissez la plateforme sur laquelle vous voulez importer votre playlist :";
$lang['playlists_login_button'] = "Ok";
$lang['playlists_cancel_button'] = "Annuler";
$lang['playlists_logged_label'] = "Vous êtes maintenant connecté.";
$lang['playlists_logout_button'] = "Déconnecter";
$lang['playlists_query_label'] = "Collez l'url de la playlist ici ...";
$lang['playlists_button'] = "Convertir";
$lang['playlists_results_found'] = "Traitement des #nb# #type# de la playlist &laquo; #iq# &raquo; ...";

// Error messages
$lang['error_503_title'] = "Vous désirez ?";
$lang['error_503'] = "Attention, on mord.";
$lang['error_404_title'] = "Page introuvable";
$lang['error_404'] = "404. Et pourtant on a cherché partout. Mais alors partout. Rien à faire. On sait pas où on l'a mise. La boulette, quoi.<br/>En même temps si vous tapiez pas n'importe quoi dans la barre d'adresse aussi ...";
$lang['error_woops_title'] = "";
$lang['error_woops'] = "500. Oui alors bon, on a peut-être fait une erreur de code qui a provoqué un bug. Ou alors le serveur vit ses dernières heures.<br />Pas d'inquiétudes. On vous tient au courant.";
$lang['error_ie_title'] = "Aïe aïe aïe.";
$lang['error_ie'] = "Le navigateur que vous utilisez a l'air un peu vieillot pour profiter pleinement du site... si vous êtes au travail, il y a de grandes chances que votre DSI tyrannise l'environnement informatique.";
$lang['error_ie_action'] = "Si vous voulez profiter de toutes les fonctionnalités du web moderne, nous vous recommandons de télécharger une version récente d'Internet Explorer ou d'un autre navigateur web. En voici une liste :";

$lang['error_ie_get'] = "Télécharger %s";

$lang['error_action'] = "Retour à l'accueil";

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

// Open Source project
$lang['os_title_long'] = "« http://tuneefy.com/t/oxtcm »";
$lang['os_paragraph_1_important'] = "Tuneefy est un projet open-source. C'est (<em>et ça restera</em>) gratuit, sans publicité, et pas besoin de login ou d'infos personnelles. Juste, ça marche (<em>la plupart du temps ...</em>).";
$lang['os_paragraph_1'] = "Mais tuneefy a un cout (certes minime) malgré tout, que nous payons avez plaisir pour offrir ce service. En gros, un serveur, un nom de domaine, ce qui fait à peu près 6€ par mois. Ce n'est pas énorme, mais c'est un coût.<br><br>

Si vous trouvez le service utile, et si vous voulez supporter son fonctionnement, nous sommes heureux d'accepter vos petits dons pour le paiement du serveur et du nom de domaine : soit <a href='http://paypal.me/tchap'>paypal.me/tchap</a> ou <a href='mailto:team@tuneefy.com'>team@tuneefy.com</a> si vous voulez nous contacter.";
$lang['os_paragraph_2'] = "Également, nous travaillons à la refonte de tuneefy, pour le rendre plus rapide, plus efficace, plus simple ... cela prend un peu de temps, et c'est <a href='https://github.com/tchapi/tuneefy-hacklang'>par ici</a> si vous voulez nous donner un coup de main pendant votre temps libre.";
$lang['os_paragraph_2_thanks'] = "Merci beaucoup ! 💙";
