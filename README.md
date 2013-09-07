### If you wanna dive into the source code, please read [this wiki page](https://github.com/tchapi/tuneefy/wiki/Before-you-criticize-...) first ...
- - - -
- - - -

# tuneefy #

tuneefy is a music sharing service allowing you to easily share tracks and albums regardless of any music platforms.

### http://tuneefy.com ###

- - - -


In a nutshell, tuneefy is a server-side proxy that will aggregate results from different music APIs, and display the consolidated result as a tracks list or albums list. Each item can then be shared.

## Server-side ##

Three main actions are done server-side :

  1. Reverse searching the query, when the user searches for a permalink (see below : [supported permalinks](#permalinks))
  2. Querying music APIs and platforms for results
  3. Sharing a specific result

#### Reverse search ####

The proxy performs a reverse-search on the query to retrieve the real title, artist and name behind a permalink

Example for the query "http://open.spotify.com/track/5jhJur5n4fasblLSCOcrTp" :
```javascript
    {
        data: {
            lookedUpPlatform: 1,
            query: "Kasabian+Test+Transmission",
            lookedUpItem: {
                name: "Test Transmission",
                artist: "Kasabian",
                album: "Kasabian",
                picture: null,
                link: "http://open.spotify.com/track/5jhJur5n4fasblLSCOcrTp"
            }
        }
    }
```

#### Fecthing data ####

The server-side proxy retrieves tracks and albums listing from the different APIs and returns them in a common format.

Example (returning data when proxying results from Deezer for the search "Jackson") :
```javascript
    {
        data: [
            {
                title: "Billie Jean",
                artist: "Michael Jackson",
                album: "HIStory - PAST, PRESENT AND FUTURE - Book I",
                picture: "http://api.deezer.com/2.0/album/71623/image",
                link: "http://www.deezer.com/track/540980",
                score: 1
            },
            {
                title: "Beat It",
                artist: "Michael Jackson",
                album: "HIStory - PAST, PRESENT AND FUTURE - Book I",
                picture: "http://api.deezer.com/2.0/album/71623/image",
                link: "http://www.deezer.com/track/541002",
                score: 0.910
            },
           
              ...

            {
                title: "Beat It",
                artist: "Michael Jackson",
                album: "Bad 25th Anniversary (Deluxe)",
                picture: "http://api.deezer.com/2.0/album/5814021/image",
                link: "http://www.deezer.com/track/59510131",
                score: 0.0899
            }
        ]
    }
```

#### Sharing ####

The server creates a permanent share link (e.g. http://tuneefy.com/t/8r2h9 or http://tuneefy.com/a/3zlp9) upon request by the client.

## Client-side ##

The main thing happening client-side is the aggregation of the proxied results to get a comprehensive list of results for the user.

The client calls the proxy for each platform the user wants to search on. This allows for :
 
  * No cross-platform restriction on the APIs (all is done server-side)
  * Results are being updated real-time when each call returns (no need to way until all calls are finished)
  * Effective Caching

Unfortunately, as a side-effect, rate-limited APIs limit the number of calls the proxy can make.

#### Aggregation method ####

Aggregation is done client-side, in two steps :

  1. `cleaning` Cleaning the title, artist, name of a track or album
  2. `hashing` Creating a hash of these info for comparison

Cleaning includes trimming of course, and finding common patterns in the strings, such as :

  * _(album version)_
  * _(featuring ...)_
  * _etc ..._

The following javascript regexp are used to find these patterns:

```javascript
    var regex_feat_p = /^(.*)(\(|\[)(\s*)(feat\s|feat\.|featuring\s|ft.|ft\s)([^\)^\]]*)(\)|\])(.*)$/gi;
    var regex_album_version = /^(.*)(\(|\[)(\s*)(album\sversion|version\salbum)([^\)^\]]*)(\)|\])(.*)$/gi;
```

The hash is currently a concatenation of the lowercased cleaned title, artist and name when available. All accented / non alphanumeric characters are replaced by their equivalent.

## A word on patterns ##

To cut a long story short, I used :

  * A `facade` patterns, with templates, to display the different pages
  * A `proxy pattern` for the data handling from the different platforms
  * An `event pooling` pattern in a (light) `MVC` pattern to display the results and aggregate them for the user

<a name="permalinks"></a>
## Supported Permalinks ##

The following permalinks are supported when searching on tuneefy :

  * __Deezer__ song link : http://www.deezer.com/listen-10236179
  * __Deezer__ artist link : http://www.deezer.com/fr/music/radiohead
  * __Deezer__ album link : http://www.deezer.com/fr/music/rjd2/deadringer-144183
  * __Spotify__ song link : http://open.spotify.com/track/5jhJur5n4fasblLSCOcrTp or spotify:track:5jhJur5n4fasblLSCOcrTp
  * __Spotify__ artist link : http://open.spotify.com/artist/6UUrUCIZtQeOf8tC0WuzRy or spotify:artist:6UUrUCIZtQeOf8tC0WuzRy
  * __Spotify__ album link : http://open.spotify.com/album/2bRcCP8NYDgO7gtRbkcqdk or spotify:album:2bRcCP8NYDgO7gtRbkcqdk
  * __Last.fm__ song link : http://www.last.fm/music/The+Clash/London+Calling/London+Calling
  * __Last.fm__ album link : http://www.last.fm/music/The+Clash/London+Calling
  * __Last.fm__ artist link : http://www.lastfm.fr/music/Sex+Pistols
  * __Grooveshark__ verbose song link : http://grooveshark.com/s/Sweet+Sweet+Heartkiller/2GVBvD?src=5
  * __Grooveshark__ album link : http://grooveshark.com/album/Impeccable+Blahs/1529354
  * __Grooveshark__ artist link : http://grooveshark.com/artist/Say+Hi+To+Your+Mom/401373
  * __Soundcloud__ simple song link : http://soundcloud.com/fuckmylife/radiohead-codex-deadmau5-cover
  * __Hypemachine__ track link : http://hypem.com/item/1g079/
  * __Youtube__ music link : http://www.youtube.com/watch?v=_FOyHhU0i7k
  * __Rdio__ link : http://www.rdio.com/#/artist/Crash_Test_Dummies
  * __Qobuz__ track link : http://player.qobuz.com/#!/track/5280111

## tuneefy bookmarklet ##

The tuneefy bookmarklet is a quick and convenient way to share tuneefy links directly from the page you're listening music in.

The standard production bookmarklet code is :

```javascript
    javascript:(function(){tuneefy_bkmrklt=document.createElement('SCRIPT');tuneefy_bkmrklt.type='text/javascript';
    tuneefy_bkmrklt.src='http://tuneefy.com/widget/share.js.php?x='+(Math.random());
    document.getElementsByTagName('head')[0].appendChild(tuneefy_bkmrklt);})();
```

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
  * Myspace (music player and artist page - first track only)
  * Spotify (in a web browser only)

## License ##

I give access to the tuneefy code under the Creative Commons Attribution-NonCommercial 3.0 Unported License (http://creativecommons.org/licenses/by-nc/3.0/legalcode).

In a nutshell, you are free to modify and distribute the code, as long as you don't make a commercial use of it, or sublicense it. You must include the following copyright notice and you cannot hold me liable if your server explodes while running this code (ahah).

> Copyright (c) (CC BY-NC) 2012-2013 Cyril Chapellier

## Contact me ##

Do not hesitate to drop me a line at tchap(@)tuneefy.com, or to fork this repo and make pull requests.
