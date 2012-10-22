# Tuneefy #

Tuneefy is a music sharing service allowing you to easily share tracks and albums regardless of any music platforms.


- - - -

## Supported Permalinks ##

The following permalinks are supported when searching on tuneefy :

 * Permalink search including :
    * __Deezer__ song link

            http://www.deezer.com/listen-10236179

    * __Deezer__ artist link

            http://www.deezer.com/fr/music/radiohead

    * __Deezer__ album link

            http://www.deezer.com/fr/music/rjd2/deadringer-144183

    * __Spotify__ song link

            http://open.spotify.com/track/5jhJur5n4fasblLSCOcrTp
            or spotify:track:5jhJur5n4fasblLSCOcrTp

    * __Spotify__ artist link

            http://open.spotify.com/artist/6UUrUCIZtQeOf8tC0WuzRy
            or spotify:artist:6UUrUCIZtQeOf8tC0WuzRy

    * __Spotify__ album link

            http://open.spotify.com/album/2bRcCP8NYDgO7gtRbkcqdk
            or spotify:album:2bRcCP8NYDgO7gtRbkcqdk

    * __Last.fm__ song link

            http://www.last.fm/music/The+Clash/London+Calling/London+Calling

    * __Last.fm__ album link

            http://www.last.fm/music/The+Clash/London+Calling

    * __Last.fm__ artist link

            http://www.lastfm.fr/music/Sex+Pistols

    * __Grooveshark__ verbose song link

            http://grooveshark.com/s/Sweet+Sweet+Heartkiller/2GVBvD?src=5

    * __Grooveshark__ album link

            http://grooveshark.com/album/Impeccable+Blahs/1529354

    * __Grooveshark__ artist link

            http://grooveshark.com/artist/Say+Hi+To+Your+Mom/401373

    * __Soundcloud__ simple song link

            http://soundcloud.com/fuckmylife/radiohead-codex-deadmau5-cover

    * __Hypemachine__ track link

            http://hypem.com/item/1g079/

    * __Youtube__ music link

            http://www.youtube.com/watch?v=_FOyHhU0i7k

    * __Rdio__ link

            http://www.rdio.com/#/artist/Crash_Test_Dummies

    * __Qobuz__ track link

            http://player.qobuz.com/#!/track/5280111

## Tuneefy bookmarklet ##

The tuneefy bookmarklet is a quick and convenient way to share tuneefy links directly from the page you're listening music in.

The standard production bookmarklet code is :

    javascript:(function(){tuneefy_bkmrklt=document.createElement('SCRIPT');tuneefy_bkmrklt.type='text/javascript';
    tuneefy_bkmrklt.src='http://tuneefy.com/widget/share.js.php?x='+(Math.random());
    document.getElementsByTagName('head')[0].appendChild(tuneefy_bkmrklt);})();

The bookmarklet recognizes what you're listening to on the following platforms / websites :

  * Deezer
  * Jiwa
  * Grooveshark
  * Radionomy
  * Stereomood
  * Musicmaze
  * Qobuz Player
  * Rdio
  * Youtube (only for tracks available on iTunes via Youtube)
  * Myspace (music player)
  * Myspace (artist page - first track only)
  * Spotify (in a web browser only)

