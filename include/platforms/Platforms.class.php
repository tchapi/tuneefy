<?php

/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                              DEEZER                                                    */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class DEEZER extends Platform
{

    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Deezer";
        $this->safe_name = "_DEEZER";
        $this->type = _LISTEN;
        $this->color = "181818";
        $this->id = $id;
        
        $this->api_key = $key;
        $this->api_secret = $secret;
        $this->api_endpoint = "http://api.deezer.com/2.0/";
        $this->api_method = "GET";
        
        $this->query_endpoint = "search";
        $this->query_term = 'q';
        $this->query_options = array( 'nb_items' => _LIMIT );
        
        $this->query_album_endpoint = "search/album";
        $this->query_album_term = $this->query_term;
        $this->query_album_options = $this->query_options;
        
        $this->album_permalink = "http://www.deezer.com/fr/album/%d";
        
        $this->lookup_endpoint = "%s/%d"; // is modified inline
        $this->lookup_term = null;
        $this->lookup_options = null;

        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
      
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "deezer.") !== false);
    }
    
    public function getNormalizedResults($itemType, $query, $limit)
    {
        $result = $this->callPlatform($itemType, $query);
        if ($result == null || !isset($result->data)) {
            return null;
        }

        $length = min(count($result->data), $limit);
        
        $data = null;
        // Normalizing each track found
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->data[$i];

            if ($itemType == 'track') { // Track
                if ($currentItem->album->cover) {
                    $picture = $currentItem->album->cover;
                } else {
                    $picture = $currentItem->artist->picture;
                }
                $data[] = array('title' => $currentItem->title,
                                'artist' => $currentItem->artist->name,
                                'album' => $currentItem->album->title,
                                'picture' => $picture,
                                'link' => web($currentItem->link),
                                'score' => round(1/($i/10+1), 2));
            } elseif ($itemType == 'album') { // Album
                if ($currentItem->cover) {
                    $picture = $currentItem->cover;
                } else {
                    $picture = $currentItem->artist->picture;
                }
                $data[] = array('title' => null,
                                'artist' => $currentItem->artist->name,
                                'album' => $currentItem->title,
                                'picture' => $picture,
                                'link' => web(sprintf($this->album_permalink, $currentItem->id)),
                                'score' => round(1/($i/10+1), 2));
            }
        }
        
        return $data;
    }
      
    public function lookupPermalink($permalink)
    {
        // http://www.deezer.com/listen-10236179
        // NOT VALID ANYMORE http://www.deezer.com/music/track/10240179
        // http://www.deezer.com/track/10444623
        $REGEX_DEEZER_TRACK = "/(listen-|music\/track\/|\/track\/)([0-9]*)$/";
        // NOT SUPPORTED ANYMORE http://www.deezer.com/fr/music/rjd2/deadringer-144183
        // http://www.deezer.com/fr/album/955330
        $REGEX_DEEZER_ALBUM = "/\/album\/([0-9]*)$/";
        // http://www.deezer.com/fr/music/radiohead
        // http://www.deezer.com/fr/artist/16948
        $REGEX_DEEZER_ARTIST = "/\/(music|artist)\/(".$this->REGEX_FULLSTRING.")$/";
        
        $track = null;
        $valid = false;

        if (preg_match($REGEX_DEEZER_TRACK, $permalink, $match)) {
            $this->lookup_endpoint = 'track/%d';
            $result = $this->callPlatform("lookup", $match[2]);
            if ($result == null) {
                return null;
            }
              
            // We encode the track to pass it on as the return track
            $track = array('name' => $result->title,
                           'artist' => $result->artist->name,
                           'album' => $result->album->title,
                           'picture' => $result->album->cover,
                           'link' => web($permalink) );
              
            // We modify the query
            $valid = true;
            $query = $track['artist']."+".$track['name'];
        } elseif (preg_match($REGEX_DEEZER_ALBUM, $permalink, $match)) {
            $this->lookup_endpoint = 'album/%d';
            $result = $this->callPlatform("lookup", $match[1]);
            if ($result == null) {
                return null;
            }

            // We encode the track to pass it on as the return track
            $track = array('name' => null,
                           'artist' => $result->artist->name,
                           'album' => $result->title,
                           'picture' => $result->cover,
                           'link' => web($permalink) );

            // We just modify the query
            $valid = true;
            $query = $track['artist']."+".$track['album'];
        } elseif (preg_match($REGEX_DEEZER_ARTIST, $permalink, $match)) {
            $this->lookup_endpoint = 'artist/%d';
            $result = $this->callPlatform("lookup", $match[2]);

            // We just modify the query
            $valid = true;

            if ($result == null || $result->error->code == 800) { // "no data" => it's not an id
                $query = $match[2];
            } else {
                $query = $result->name;
            }
        }
      
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
    }
    
    // Playlists functions
    public function retrievePlaylist($permalink)
    {
        // http://www.deezer.com/en/music/playlist/20452358
        $REGEX_DEEZER_PLAYLIST = "/music\/playlist\/([0-9]*)$/";
        
        if (!preg_match($REGEX_DEEZER_PLAYLIST, $permalink, $match)) {
            return null;
        }
      
        $call = array("url" => $this->api_endpoint."playlist/".$match[1],
                      "data" => array('nb_items' => "999999") ,
                      "method" => "GET");
      
        $result = $this->makeCall($call, $this->needsOAuth);

        if ($result == null) {
            return null;
        }
        
        $length = count($result->tracks->data);
        
        $data = null;
        // Normalizing each track found
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->tracks->data[$i];

            $data[] = array('seq' => $i,
                            'title' => $currentItem->title,
                            'artist' => $currentItem->artist->name,
                            'album' => $currentItem->album->title);
        }
        
        return array('title' => $result->title, 'count' => count($data),'tracks' => $data);
    }
    
    public function createPlaylist($title, $accessToken)
    {
        // FIX ME
        return false;
    }
}

/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                              SPOTIFY                                                   */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class SPOTIFY extends Platform
{

    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Spotify";
        $this->safe_name = "_SPOTIFY";
        $this->type = _LISTEN;
        $this->color = "4DA400";
        $this->id = $id;

        $this->api_key = $key;
        $this->api_endpoint = "http://api.spotify.com/v1/";
        $this->api_method = "GET";
        
        $this->query_endpoint = "search";
        $this->query_term = 'q';
        $this->query_options = array("type" => "track");
        
        $this->query_album_endpoint = "search";
        $this->query_album_term = $this->query_term;
        $this->query_album_options = array( "type" => "album");
        
        $this->lookup_endpoint = "%ss/%s";
        $this->lookup_term = null;
        $this->lookup_options = null;

        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
    
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "spotify:") !== false || strpos($permalink, "open.spotify.") !== false || strpos($permalink, "play.spotify.") !== false);
    }
    
    public function getNormalizedResults($itemType, $query, $limit)
    {
        $result = $this->callPlatform($itemType, $query);
        if ($result == null) {
            return null;
        }

        if ($itemType == 'track') {
            $results = $result->tracks->items;
        }
        if ($itemType == 'album') {
            $results = $result->albums->items;
        }
        
        $length = min(count($results), $limit);
        
        $data = null;
        
        if ($length > 0 && $itemType == 'track') {
            $maxPopularity = $results[0]->popularity;
            if ($maxPopularity == 0) {
                $maxPopularity = 1;
            }
        }

        if ($itemType == 'album') {
            $artists = array();
            for ($j=0; $j < $length; $j++) {
                $artists[$results[$j]->id] = $results[$j]->id;
            }

            // We need to get the artist for each album, FUCK YOU Spotify, you API is SHIT
            $this->lookup_endpoint = "albums";
            $this->lookup_term = "ids";
            $result_artists = $this->callPlatform("lookup", implode($artists, ","));

            for ($k=0; $k < count($result_artists->albums); $k++) {
                $artists[$result_artists->albums[$k]->id] = $result_artists->albums[$k]->artists[0]->name;
            }
        }

        for ($i=0; $i<$length; $i++) {
            $currentItem = $results[$i];

            if ($itemType == 'track') { // Track
                $images = $currentItem->album->images;
                $data[] = array('title' => $currentItem->name,
                                'artist' => $currentItem->artists[0]->name,
                                'album' => $currentItem->album->name,
                                'picture' => (count($images) > 1? $images[1]->url:null),
                                'link' => web($currentItem->external_urls->spotify),
                                'score' => round($currentItem->popularity/$maxPopularity, 2) );
            } elseif ($itemType == 'album') { // Album
                $images = $currentItem->images;
                $data[] = array('title' => null,
                                'artist' => $artists[$currentItem->id], // Motherfucking Spotify !!
                                'album' => $currentItem->name,
                                'picture' => (count($images) > 1? $images[1]->url:null),
                                'link' => web($currentItem->external_urls->spotify),
                                'score' => round(1/($i/10+1), 2));
            }
        }
        
        return $data;
    }
    
    public function lookupPermalink($permalink)
    {
        // http://open.spotify.com/track/5jhJur5n4fasblLSCOcrTp
        $REGEX_SPOTIFY_ALL = "/(artist|album|track)(:|\/)([a-zA-Z0-9]*)$/";
        
        // LOCAL files : http://open.spotify.com/local/hang+the+bastard/raw+sorcery/doomed+fucking+doomed/206
        $REGEX_SPOTIFY_LOCAL = "/local\/".$this->REGEX_FULLSTRING."\/".$this->REGEX_FULLSTRING."\/".$this->REGEX_FULLSTRING."\/[0-9]+$/";
        
        $track = null;
        $valid = false;
        
        if (preg_match($REGEX_SPOTIFY_ALL, $permalink, $match)) {
            // We have a nicely formatted share url
          
            $requestType = trim($match[1]);

            // We need to change the endpoint according to the type
            $this->lookup_endpoint = $requestType . 's/%s';

            $result = $this->callPlatform("lookup", $match[3]);
            if ($result == null) {
                return null;
            }
      
            $valid = true;

            if ($requestType == 'track') {
                // We encode the track to pass it on as the return track
                $track = array('name' => $result->name,
                               'artist' => $result->artists[0]->name,
                               'album' => $result->album->name,
                               'picture' => $result->album->images[1]->url,
                               'link' => web($permalink) );
              
                // We modify the query
                $query = $track['artist']."+".$track['name'];
            } elseif ($requestType == 'album') {
                $query = $result->artists[0]->name."+".$result->name;
            } elseif ($requestType == 'artist') {
                $query = $result->name;
            }
        } elseif (preg_match($REGEX_SPOTIFY_LOCAL, $permalink, $match)) {
            // We have a nicely formatted local url
          
            $valid = true;
            $query = $match[1]."+".$match[3];
        }
        
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
    }
}

/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                              LAST.FM                                                   */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class LASTFM extends Platform
{

    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Last.fm";
        $this->safe_name = "_LASTFM";
        $this->type = _LISTEN;
        $this->color = "e41c1c";
        $this->id = $id;

        $this->api_key = $key;
        $this->api_endpoint = "http://ws.audioscrobbler.com/2.0/";
        $this->api_method = "GET";
        
        $this->query_endpoint = "";
        $this->query_term = 'track';
        $this->query_options = array('method' => "track.search", 'format' => "json", 'limit' => _LIMIT, 'api_key' => $this->api_key);
     
        $this->query_album_endpoint = $this->query_endpoint;
        $this->query_album_term = 'album';
        $this->query_album_options = array('method' => "album.search", 'format' => "json", 'limit' => _LIMIT, 'api_key' => $this->api_key);
        
        $this->lookup_endpoint = "";
        $this->lookup_term = 'track';
        $this->lookup_options = array('method' => "track.search", 'format' => "json", 'limit' => 1, 'api_key' => $this->api_key);

        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
      
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "lastfm.") !== false || strpos($permalink, "last.fm") !== false);
    }
    
    public function getNormalizedResults($itemType, $query, $limit)
    {
        $result = $this->callPlatform($itemType, $query);
        if ($result == null) {
            return null;
        }

        $data = null;
        
        if ($itemType == 'track') {
            if (isset($result->results) && $result->results->trackmatches != '\n' && isset($result->results->trackmatches->track)) {
                $results = $result->results->trackmatches->track;

                if (is_array($results) && count($results) > 0) {// so number of items > 1
                    $maxPopularity = $results[0]->listeners;
                } elseif (count($results) > 0) { // number of items = 1
                    $maxPopularity = $results->listeners;
                } else {
                    $results = null;
                }
            } else {
                $results = null;
            }
        } elseif ($itemType == 'album') {
            if (isset($result->results) && $result->results->albummatches != '\n' && isset($result->results->albummatches->album)) {
                $results = $result->results->albummatches->album;
            } else {
                $results = null;
            }
        }
        
        $length = min(count($results), $limit);
        
        for ($i=0; $i<$length; $i++) {
            $currentItem = $results[$i];
              
            if (!is_null($currentItem)) {
                if (isset($currentItem->image)) {
                    $picture = $currentItem->image[2]->{'#text'}; // medium size
                } else {
                    $picture = null;
                }
                  
                if ($itemType == 'track') {
                    $data[] = array('title' => $currentItem->name,
                                    'artist' => $currentItem->artist,
                                    'album' => null,
                                    'picture' => $picture,
                                    'link' => web($currentItem->url),
                                    'score' => round($currentItem->listeners/$maxPopularity, 2) );
                } elseif ($itemType == 'album') {
                    $data[] = array('title' => null,
                                    'artist' => $currentItem->artist,
                                    'album' => $currentItem->name,
                                    'picture' => $picture,
                                    'link' => web($currentItem->url),
                                    'score' => round(1/($i/10+1), 2));
                }
            }
        }
        
        return $data;
    }

    public function lookupPermalink($permalink)
    {
        // http://www.lastfm.fr/music/The+Clash/London+Calling
        $REGEX_LASTFM_ALBUM = "/music\/".$this->REGEX_FULLSTRING."\/".$this->REGEX_FULLSTRING."$/";
        // http://www.lastfm.fr/music/Sex+Pistols
        $REGEX_LASTFM_ARTIST = "/music\/".$this->REGEX_FULLSTRING."$/";
        // http://www.lastfm.fr/music/The+Clash/London+Calling/London+Calling
        $REGEX_LASTFM_TRACK = "/music\/".$this->REGEX_FULLSTRING."\/".$this->REGEX_FULLSTRING."\/".$this->REGEX_FULLSTRING."$/";
        
        $track = null;
        $valid = false;
        
        if (preg_match($REGEX_LASTFM_TRACK, $permalink, $match)) {
            // A little dirty but ...
            $this->lookup_options = $this->lookup_options + array("artist" => $match[1]);
            
            $result = $this->callPlatform("lookup", $match[3]);
            if ($result == null) {
                return null;
            }
      
            $valid = true;

            if ($result->results->{"opensearch:totalResults"} != 0) {
                $result = $result->results->trackmatches->track;

                if ($result == null || count($result) == 0) {
                    // We modify the query
                    $query = $match[1]."+".$match[2]."+".$match[3];
                } else {
                    if ($match[2] != '_') {
                        $album = $match[2];
                    } else {
                        $album = null;
                    }

                    // We encode the track to pass it on as the return track
                    $track = array('name' => $result[0]->name,
                                   'artist' => $result[0]->artist,
                                   'album' => $album,
                                   'picture' => null,
                                   'link' => web($permalink) );
                  
                    // We modify the query
                    $query = $track['artist'].($album!=null?"+".$album:"")."+".$track['name'];
                }
            } else {
                // We modify the query
                $query = $match[1]."+".$match[2]."+".$match[3];
            }
        } elseif (preg_match($REGEX_LASTFM_ALBUM, $permalink, $match)) {
            $valid = true;
            
            // We modify the query
            $query = str_replace("-", "+", $match[1].'+'.$match[2]);
        } elseif (preg_match($REGEX_LASTFM_ARTIST, $permalink, $match)) {
            $valid = true;
            
            // We modify the query
            $query = str_replace("-", "+", $match[1]);
        }
        
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
    }
}

/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                             GROOVESHARK                                                */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class GROOVESHARK extends Platform
{
    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Grooveshark";
        $this->safe_name = "_GROOVESHARK";
        $this->type = _LISTEN;
        $this->color = "999999";
        $this->id = $id;

        $this->api_key = $key;
        $this->api_endpoint = "http://tinysong.com/";
        $this->api_secret = $secret;
        $this->api_method = "GET";
        
        $this->query_endpoint = "s/%s";
        $this->query_term = null;
        $this->query_options = array('format' => "json", 'limit' => _LIMIT, 'key' => $this->api_key);
        
        $this->query_album_endpoint = null;
        $this->query_album_term = null;
        $this->query_album_options = null;
        
        $this->lookup_endpoint = null;
        $this->lookup_term = null;
        $this->lookup_options = null;

        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
  
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "grooveshark.") !== false);
    }
   
    public function getNormalizedResults($itemType, $query, $limit)
    {
        $result = $this->callPlatform($itemType, $query);
        if ($result == null) {
            return null;
        }

        $data = null;
        
        $length = min(count($result), $limit);
          
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result[$i];
            
            $data[] = array('title' => $currentItem->SongName,
                            'artist' => $currentItem->ArtistName,
                            'album' => $currentItem->AlbumName,
                            'picture' => null,
                            'link' => web($currentItem->Url),
                            'score' => round(1/($i/10+1), 2) );
        }
        
        return $data;
    }

    public function lookupPermalink($permalink)
    {
        // http://grooveshark.com/s/Sweet+Sweet+Heartkiller/2GVBvD?src=5
        $REGEX_GROOVESHARK_TRACK = "/\/s\/".$this->REGEX_FULLSTRING."\/([a-zA-Z0-9]*)\?([a-zA-Z0-9%\+\=-]*)$/";
        // http://grooveshark.com/album/Impeccable+Blahs/1529354
        $REGEX_GROOVESHARK_ALBUM_ARTIST = "/\/(album|artist)\/".$this->REGEX_FULLSTRING."\/([0-9]*)$/";
        
        $track = null;
        $valid = false;
        
        if (preg_match($REGEX_GROOVESHARK_TRACK, $permalink, $match)) {
            $valid = true;

            // We modify the query
            if ($match[1] != '-') {
                $query = str_replace("-", "+", $match[1]);
            }
        } elseif (preg_match($REGEX_GROOVESHARK_ALBUM_ARTIST, $permalink, $match)) {
            $valid = true;
            
            // Link for an album or artist - we know the name of the album or artist (c'est deja ca)
            // $requestType = trim($match[1]); // For future use
            // $requestId = trim($match[3]); // For future use
            
            // We modify the query
            $query = str_replace("-", "+", $match[2]);
        }
        
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
        
    }
}

/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                              JIWA                                                      */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class JIWA extends Platform
{
    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Jiwa";
        $this->safe_name = "_JIWA";
        $this->type = _LISTEN;
        $this->color = "0983da";
        $this->id = $id;

        $this->api_key = $key;
        $this->api_endpoint = "http://www.jiwa.fr/";
        $this->api_method = "GET";
        
        $this->query_endpoint = "track/search";
        $this->query_term = 'q';
        $this->query_options = array('noRestricted' => "true", 'limit' => _LIMIT, 'sort' => "songPopularity");
        
        $this->query_album_endpoint = null;
        $this->query_album_term = null;
        $this->query_album_options = null;
        
        $this->lookup_endpoint = "%s/details";
        $this->lookup_term = "%sId";
        $this->lookup_options = array('languageId' => 1);

        $this->track_permalink = "http://www.jiwa.fr/#track/%d";
        
        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
  
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "jiwa.") !== false);
    }

    public function getNormalizedResults($itemType, $query, $limit)
    {
        $result = $this->callPlatform($itemType, $query);
        if ($result == null) {
            return null;
        }

        $data = null;
        
        $length = min(count($result->page), $limit);
          
        if ($length >0) {
            $maxPopularity = intval($result->page[0]->songPopularity);
        }
        
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->page[$i];
            $popularity = ($maxPopularity==0)?round(1/($i/10+1), 2):round($currentItem->songPopularity/$maxPopularity, 2);
            
            $data[] = array('title' => $currentItem->songName,
                            'artist' => $currentItem->artistName,
                            'album' => $currentItem->albumName,
                            'picture' => null,
                            'link' => web(sprintf($this->track_permalink, $currentItem->trackId)),
                            'score' => $popularity );
        }
        
        return $data;
    }

    public function lookupPermalink($permalink)
    {
        // http://www.jiwa.fr/#track/52575 ou http://www.jiwa.fr/#album/328122
        $REGEX_JIWA_ALL = "/\#(track|album|artist)\/([0-9]*)$/";
        // http://jiwa.fr/track/The-Who-749/Who-s-Next-70366/Baba-O-Riley-400388.html
        $REGEX_JIWA_TRACK_PERMALINK = "/track\/(.*)\-([0-9]*).html$/";
        
        $track = null;
        $valid = false;
        
        if (preg_match($REGEX_JIWA_ALL, $permalink, $match)) {
            // A little dirty but ...
            $requestType = trim($match[1]);
            $this->lookup_endpoint = sprintf($this->lookup_endpoint, $requestType);
            $this->lookup_term = sprintf($this->lookup_term, $requestType);
            
            $result = $this->callPlatform("lookup", $match[2]);
            if ($result == null) {
                return null;
            }
      
            $valid = true;
                   
            if ($requestType == 'track') {
                // We encode the track to pass it on as the return track
                $track = array('name' => $result->track->songName,
                               'artist' => $result->track->artistName,
                               'album' => $result->track->albumName,
                               'picture' => null,
                               'link' => web($permalink) );
                
                // We modify the query
                $query = $track['artist']."+".$track['name'];
            } elseif ($requestType == 'album') {
                // We modify the query
                $query = str_replace("-", "+", $result->album->albumName."+".$result->album->artistName);
            } elseif ($requestType == 'artist') {
                // We modify the query
                $query = str_replace("-", "+", $result->artist->artistName);
            }
        } elseif (preg_match($REGEX_JIWA_TRACK_PERMALINK, $permalink, $match)) {
            // A little dirty but ...
            $this->lookup_endpoint = sprintf($this->lookup_endpoint, "track");
            $this->lookup_term = sprintf($this->lookup_term, "track");
            
            $result = $this->callPlatform("lookup", $match[2]);
            if ($result == null) {
                return null;
            }
            
            $valid = true;
            
            // We encode the track to pass it on as the return track
            $track = array('name' => $result->track->songName,
                           'artist' => $result->track->artistName,
                           'album' => $result->track->albumName,
                           'picture' => null,
                           'link' => web(sprintf($this->track_permalink, $match[2])));
              
            // We modify the query
            $query = $track['artist']."+".$track['name'];
        }
        
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
        
    }
}

/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                              SOUNDCLOUD                                                */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class SOUNDCLOUD extends Platform
{
    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Soundcloud";
        $this->safe_name = "_SOUNDCLOUD";
        $this->type = _LISTEN;
        $this->color = "ff6600";
        $this->id = $id;

        $this->api_key = $key;
        $this->api_endpoint = "https://api.soundcloud.com/";
        $this->api_method = "GET";
        
        $this->query_endpoint = "tracks.json";
        $this->query_term = 'q';
        $this->query_options = array('order' => "hotness", 'consumer_key' => $this->api_key);
        
        $this->query_album_endpoint = null;
        $this->query_album_term = null;
        $this->query_album_options = null;
        
        $this->lookup_endpoint = null;
        $this->lookup_term = null;
        $this->lookup_options = null;

        $this->track_permalink = null;
        
        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
    
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "soundcloud.") !== false);
    }

    public function getNormalizedResults($itemType, $query, $limit)
    {
        $result = $this->callPlatform($itemType, $query);
        if ($result == null) {
            return null;
        }

        $data = null;
        
        $length = min(count($result), $limit);
          
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result[$i];
            
            $data[] = array('title' => $currentItem->title,
                            'artist' => $currentItem->user->username,
                            'album' => null,
                            'picture' => $currentItem->artwork_url,
                            'link' => web($currentItem->permalink_url),
                            'score' => round(1/($i/10+1), 2) );
        }
        
        return $data;
    }

    public function lookupPermalink($permalink)
    {
        $REGEX_SOUNDCLOUD_ALL = "/\/".$this->REGEX_FULLSTRING."\/".$this->REGEX_FULLSTRING."$/";
      
        $track = null;
        $valid = false;
        
        if (preg_match($REGEX_SOUNDCLOUD_ALL, $permalink, $match)) {
            $valid = true;
            
            // We modify the query
            $query = str_replace("-", "+", $match[2]);
        }
            
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
          
    }

    // Playlists functions
    public function retrievePlaylist($permalink)
    {
        // http://soundcloud.com/78-tours/sets/78-tours-playlist/
        $REGEX_SOUNDCLOUD_PLAYLIST = "/soundcloud\.com\/[^\/]*\/sets\/([^\/]*)/";
        
        if (!preg_match($REGEX_SOUNDCLOUD_PLAYLIST, $permalink, $match)) {
            return null;
        }

        $call = array("url" => $this->api_endpoint."playlists/".$match[1].".json",
                      "data" => array('client_id' => $this->api_key, 'nb_items' => "999999") ,
                      "method" => "GET");

        $result = $this->makeCall($call, $this->needsOAuth);

        if ($result == null) {
            return null;
        }
        
        $length = count($result->tracks);
        
        $data = null;
        // Normalizing each track found
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->tracks[$i];

            $data[] = array('seq' => $i,
                            'title' => $currentItem->title,
                            'artist' => $currentItem->user->username,
                            'album' => null);
        }
        
        return array('title' => $result->title, 'count' => count($data),'tracks' => $data);
    }
}

/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                              HYPEMACHINE                                               */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class HYPEMACHINE extends Platform
{

    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Hypemachine";
        $this->safe_name = "_HYPEMACHINE";
        $this->type = _LISTEN;
        $this->color = "83C441";
        $this->id = $id;

        $this->api_key = $key;
        $this->api_endpoint = "http://hypem.com/";
        $this->api_method = "GET";
        
        $this->query_endpoint = "playlist/search/%s/json/1/data.js";
        $this->query_term = null;
        $this->query_options = null;
        
        $this->query_album_endpoint = null;
        $this->query_album_term = null;
        $this->query_album_options = null;
        
        $this->lookup_endpoint = "playlist/item/%s/json/1/data.js";
        $this->lookup_term = null;
        $this->lookup_options = null;

        $this->track_permalink = "http://hypem.com/item/%s";
        
        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
    
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "hypem.") !== false);
    }

    public function getNormalizedResults($itemType, $query, $limit)
    {
        $result = $this->callPlatform($itemType, $query);
        if ($result == null) {
            return null;
        }

        $data = null;
        
        $length = min(count((array) $result)-1, $limit);

        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->{"$i"};
            
            $data[] = array('title' => $currentItem->title,
                            'artist' => $currentItem->artist,
                            'album' => null,
                            'picture' => null,
                            'link' => web(sprintf($this->track_permalink, $currentItem->mediaid)),
                            'score' => round(1/($i/10+1), 2) );
        }
        
        return $data;
    }

    public function lookupPermalink($permalink)
    {
        // http://hypem.com/item/1arwr/Digitalism+-+2+Hearts
        $REGEX_HYPEM_TRACK = "/\/item\/([0-9a-zA-Z]*)(|\/".$this->REGEX_FULLSTRING.")$/";
        // http://hypem.com/artist/Digitalism
        $REGEX_HYPEM_ARTIST = "/\/artist\/".$this->REGEX_FULLSTRING."$/";
        
        $track = null;
        $valid = false;
        
        if (preg_match($REGEX_HYPEM_TRACK, $permalink, $match)) {
            $result = $this->callPlatform("lookup", $match[1]);
            if ($result == null) {
                return null;
            }
            
            $valid = true;
            
            $track = array('name' => $result->{"0"}->title,
                           'artist' => $result->{"0"}->artist,
                           'album' => null,
                           'picture' => null,
                           'link' => web(sprintf($this->track_permalink, $result->{"0"}->mediaid)) );
            
            // We modify the query
            $query = $track['artist']."+".$track['name'];
        } elseif (preg_match($REGEX_HYPEM_ARTIST, $permalink, $match)) {
            $valid = true;
            
            // We modify the query
            $query = str_replace("-", "+", $match[1]);
        }
            
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
    }
}


/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                              MOG                                                       */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class MOG extends Platform
{
    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "MOG";
        $this->safe_name = "_MOG";
        $this->type = _LISTEN;
        $this->color = "232323";
        $this->id = $id;
        
        $this->api_key = $key;
        $this->api_endpoint = "http://api.mog.com/v2/";
        $this->api_method = "GET";
        
        $this->query_endpoint = null;
        $this->query_term = null;
        $this->query_options = null;
        
        $this->query_album_endpoint = null;
        $this->query_album_term = null;
        $this->query_album_options = null;
        
        $this->lookup_endpoint = "tracks/%s.json";
        $this->lookup_term = null;
        $this->lookup_options = null;

        $this->track_permalink = "http://mog.com/tracks/mn";
        
        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
    
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "mog.com") !== false);
    }

    public function getNormalizedResults($itemType, $query, $limit)
    {
        return null;
    }
      
    public function lookupPermalink($permalink)
    {
        // http://mog.com/m/track/43477697
        $REGEX_MOG_TRACK = "/track\/([0-9]*)$/";
        
        $track = null;
        $valid = false;
        
        if (preg_match($REGEX_MOG_TRACK, $permalink, $match)) {
            $result = $this->callPlatform("lookup", $match[1]);
            if ($result == null) {
                return null;
            }
            
            $valid = true;
            
            $track = array('name' => $result->track_name,
                           'artist' => $result->artist_name,
                           'album' => $result->album_name,
                           'picture' => $result->album_image,
                           'link' => web($permalink) );
            
            // We modify the query
            $query = $track['artist']."+".$track['name'];
        }
            
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
    }
}

/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                              MIXCLOUD                                                  */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class MIXCLOUD extends Platform
{

    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Mixcloud";
        $this->safe_name = "_MIXCLOUD";
        $this->type = _LISTEN;
        $this->color = "afd8db";
        $this->id = $id;

        $this->api_key = $key;
        $this->api_endpoint = "http://api.mixcloud.com/";
        $this->api_method = "GET";
        
        $this->query_endpoint = "search";
        $this->query_term = 'q';
        $this->query_options = array('type' => "cloudcast", 'limit' => _LIMIT);
        
        $this->query_album_endpoint = null;
        $this->query_album_term = null;
        $this->query_album_options = null;
        
        $this->lookup_endpoint = null;
        $this->lookup_term = null;
        $this->lookup_options = null;

        $this->track_permalink = null;
        
        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
    
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "mixcloud.") !== false);
    }

    public function getNormalizedResults($itemType, $query, $limit)
    {
        $result = $this->callPlatform($itemType, $query);
        if ($result == null) {
            return null;
        }

        $data = null;
        
        $length = min(count($result->data), $limit);
          
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->data[$i];
            
            $data[] = array('title' => $currentItem->name,
                            'artist' => $currentItem->user->name,
                            'album' => null,
                            'picture' => $currentItem->pictures->medium,
                            'link' => web($currentItem->url),
                            'score' => round(1/($i/10+1), 2) );
        }
        return $data;
    }
    
    public function lookupPermalink($permalink)
    {
        $REGEX_MIXCLOUD_ALL = "/\/".$this->REGEX_FULLSTRING."\/".$this->REGEX_FULLSTRING."$/";
      
        $track = null;
        $valid = false;
        
        if (preg_match($REGEX_MIXCLOUD_ALL, $permalink, $match)) {
            $valid = true;
            
            // We modify the query
            $query = str_replace("-", "+", $match[2]);
        }
            
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
    }

    // Playlists functions
    public function retrievePlaylist($permalink)
    {
        // http://www.mixcloud.com/theclosersound/january-uk-funky-mix/
        $REGEX_MIXCLOUD_PLAYLIST = "/mixcloud\.com\/([^\/]*)\/([^\/]*)/";
        
        if (!preg_match($REGEX_MIXCLOUD_PLAYLIST, $permalink, $match) || $match[1] == 'tag'  || $match[1] == 'artist'  || $match[1] == 'track'  || $match[1] == 'categories') {
            return null;
        }

        $call = array("url" => $this->api_endpoint.$match[1]."/".$match[2],
                      "data" => null,
                      "method" => "GET");

        $result = $this->makeCall($call, $this->needsOAuth);

        if ($result == null) {
            return null;
        }
        
        $length = count($result->sections);
        
        $data = null;
        // Normalizing each track found
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->sections[$i]->track;

            $data[] = array('seq' => $i,
                            'title' => $currentItem->name,
                            'artist' => $currentItem->artist->name,
                            'album' => null);
        }
        
        return array('title' => $result->name, 'count' => count($data),'tracks' => $data);
    }
}

/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                              YOUTUBE                                                   */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class YOUTUBE extends Platform
{
    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Youtube";
        $this->safe_name = "_YOUTUBE";
        $this->type = _LISTEN;
        $this->color = "c8120b";
        $this->id = $id;

        $this->api_key = $key;
        $this->api_endpoint = "https://www.googleapis.com/youtube/v3/";
        $this->api_method = "GET";
        
        $this->query_endpoint = "search";
        $this->query_term = 'q';
        // We cannot go beyond 50 results (YOUTUBE LIMITATION)
        $this->query_options = array('key' => $this->api_key, 'part' => 'snippet', 'order' => "relevance", 'maxResults' => min(50, _LIMIT),
                                     'videoCategoryId' => "10", 'alt' => "json", "type" => 'video', "topicId" => "/m/04rlf");
                                     
        $this->query_album_endpoint = null;
        $this->query_album_term = null;
        $this->query_album_options = null;
        
        $this->lookup_endpoint = "videos";
        $this->lookup_term = 'id';
        $this->lookup_options = array('key' => $this->api_key, 'part' => 'snippet', 'alt' => "json");

        $this->track_permalink = "http://youtube.com/watch?v=%s";
        
        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
    
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "youtube.") !== false);
    }

    public function getNormalizedResults($itemType, $query, $limit)
    {
        $result = $this->callPlatform($itemType, $query);
        if ($result == null) {
            return null;
        }

        $data = null;
        
        if (count($result->items) > 0) {
            $length = min(count($result->items), $limit);
        } else {
            return null;
        }

        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->items[$i];
            
            if (preg_match("/([^-]*)-([^-^\(\[]*).*/", $currentItem->snippet->title, $meta)) {
                $title = trim($meta[2]);
                $artist = trim($meta[1]);
                $id = $currentItem->id->videoId;
            } else {
                // We can't parse the title => we will not have enough info on the track
                continue;
            }
            
            $data[] = array('title' => $title,
                            'artist' => $artist,
                            'album' => null,
                            'picture' => $currentItem->snippet->thumbnails->medium->url,
                            'link' => web(sprintf($this->track_permalink, $id)),
                            'score' => round(1/($i/10+1), 2) );
        }
        
        return $data;
    }
    
    public function lookupPermalink($permalink)
    {
        $REGEX_YOUTUBE_ALL = "/\/watch\?v\=([a-zA-Z0-9\-\_]*)(|\&(.*))$/";
        
        $track = null;
        $valid = false;
        
        if (preg_match($REGEX_YOUTUBE_ALL, $permalink, $match)) {
            $result = $this->callPlatform("lookup", $match[1]);
            if ($result == null) {
                return null;
            }
            
            $currentItem = $result->items[0];

            if (preg_match("/([^-]*)-([^-^\(\[]*).*/", $currentItem->snippet->title, $meta)) {
                $title = trim($meta[2]);
                $artist = trim($meta[1]);
                $valid = true;
            } else {
                // We can't parse the title => we will not have enough info on the track
                return null;
            }
            
            $track = array( 'name' => $title,
                            'artist' => $artist,
                            'album' => null,
                            'picture' => $currentItem->snippet->thumbnails->medium->url,
                            'link' => web(sprintf($this->track_permalink, $currentItem->id)) );
            
            // We modify the query
            $query = $track['artist']."+".$track['name'];
        }
            
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
    }

    // Playlists functions
    public function retrievePlaylist($permalink)
    {
        // http://www.youtube.com/playlist?list=ALBTKoXRg38BBkvaILOUfmgKiaIolWHGay&feature=plpp
        $REGEX_YOUTUBE_PLAYLIST = "/youtube\.com\/playlist\?list\=([^\/\&]*)/";
        
        if (!preg_match($REGEX_YOUTUBE_PLAYLIST, $permalink, $match)) {
            return null;
        }

        // FIX ME
        return null;
    }
}

/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                              ITUNES                                                    */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class ITUNES extends Platform
{
    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "iTunes";
        $this->safe_name = "_ITUNES";
        $this->type = _BUY;
        $this->color = "216be4";
        $this->id = $id;

        $this->api_key = $key;
        $this->api_endpoint = "http://itunes.apple.com/";
        $this->api_method = "GET";
        
        $this->query_endpoint = "search";
        $this->query_term = 'term';
        $this->query_options = array('media' => "music", 'entity' => "musicTrack", 'limit' => _LIMIT);
        
        $this->query_album_endpoint = $this->query_endpoint;
        $this->query_album_term = $this->query_term;
        $this->query_album_options = array('media' => "music", 'entity' => "album", 'limit' => _LIMIT);
        
        $this->lookup_endpoint = null;
        $this->lookup_term = null;
        $this->lookup_options = null;

        $this->track_permalink = null;
        
        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
    
    public function hasPermalink($permalink)
    {
        return false;
    }

    public function getNormalizedResults($itemType, $query, $limit)
    {
        $result = $this->callPlatform($itemType, $query);
        if ($result == null) {
            return null;
        }

        $data = null;
        
        $length = min(count($result->results), $limit);
        
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->results[$i];
            
            if (isset($currentItem->collectionName)) {
                $album = $currentItem->collectionName;
            } else {
                $album = null;
            }
            
            if ($itemType == 'track') {
                if (strpos($currentItem->trackViewUrl, "?") === false) {
                    $currentItem->trackViewUrl .= "?app=music";
                } else {
                    $currentItem->trackViewUrl .= "&app=music";
                }

                $data[] = array('title' => $currentItem->trackName,
                                'artist' => $currentItem->artistName,
                                'album' => $album,
                                'picture' => $currentItem->artworkUrl100,
                                'link' => web($currentItem->trackViewUrl),
                                'score' => round(1/($i/10+1), 2) );
            } elseif ($itemType == 'album') {
                if (strpos($currentItem->collectionViewUrl, "?") === false) {
                    $currentItem->collectionViewUrl .= "?app=music";
                } else {
                    $currentItem->collectionViewUrl .= "&app=music";
                }
                
                $data[] = array('title' => null,
                                'artist' => $currentItem->artistName,
                                'album' => $album,
                                'picture' => $currentItem->artworkUrl100,
                                'link' => web($currentItem->collectionViewUrl),
                                'score' => round(1/($i/10+1), 2));
            }
        }
        return $data;
    }
  
    public function lookupPermalink($permalink)
    {
        return null;
    }
}

/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                              ECHONEST                                                  */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class ECHONEST extends Platform
{
    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Echonest";
        $this->safe_name = "_ECHONEST";
        $this->type = _META;
        $this->color= "1e232b";
        $this->id = $id;

        $this->api_key = $key;
        $this->api_endpoint = "http://developer.echonest.com/api/v4/";
        $this->api_method = "GET";
        
        $this->query_endpoint = "song/search";
        $this->query_term = 'title';
        $this->query_options = array('api_key' => $this->api_key, 'format' => "json", 'start' => 0,  'sort' => "song_hotttnesss-desc", 'bucket' => "audio_summary", 'results' => _LIMIT);
        
        $this->query_album_endpoint = null;
        $this->query_album_term = null;
        $this->query_album_options = null;
        
        $this->lookup_endpoint = null;
        $this->lookup_term = null;
        $this->lookup_options = null;

        $this->track_permalink = null;
        
        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
    
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "itunes.") !== false);
    }

    public function getNormalizedResults($itemType, $query, $limit)
    {
        $result = $this->callPlatform($itemType, $query);
        if ($result == null) {
            return null;
        }

        $data = null;
        
        $length = min(count($result->response->songs), $limit);
          
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->response->songs[$i];
            
            $data[] = array('title' => $currentItem->title,
                            'artist' => $currentItem->artist_name,
                            'album' => null,
                            'picture' => null,
                            'link' => $currentItem->id,
                            'score' => round(1/($i/10+1), 2) );
        }
        
        return $data;
    }
    
    public function lookupPermalink($permalink)
    {
        return null;
    }
}





/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                              RDIO                                                      */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class RDIO extends Platform
{

    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Rdio";
        $this->safe_name = "_RDIO";
        $this->type = _LISTEN;
        $this->color = "2fb9fd";
        $this->id = $id;

        $this->api_key = $key;
        $this->needsOAuth2 = true;
        $this->api_secret = $secret;
        
        $this->api_endpoint = "https://services.rdio.com/api/1/";
        $this->api_method = "POST";
        
        $this->query_endpoint = "";
        $this->query_term = "query";
        $this->query_options = array("method" => 'search', "types" => 'Track', "count" => _LIMIT);
        
        $this->query_album_endpoint = $this->query_endpoint;
        $this->query_album_term = $this->query_term;
        $this->query_album_options = array("method" => 'search', "types" => 'Album', "count" => _LIMIT);
        
        $this->lookup_endpoint = "";
        $this->lookup_term = "keys";
        $this->lookup_options = array("method" => 'get');

        $this->track_permalink = null;
        
        $this->oauth2_endpoint = "https://services.rdio.com/oauth2/token";
        $this->oauth2_access_token_term = "access_token";
        $this->oauth2_params = array("grant_type" => 'client_credentials', "client_id" => $this->api_key, "client_secret" => $this->api_secret);

        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
    
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "rdio.") !== false);
    }

    public function getNormalizedResults($itemType, $query, $limit)
    {
        $result = $this->callPlatform($itemType, $query);
        if ($result == null) {
            return null;
        }

        $data = null;
        
        $length = min(count($result->result->results), $limit);
        
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->result->results[$i];
            
            if ($itemType == 'track') {
                $data[] = array('title' => $currentItem->name,
                                'artist' => $currentItem->artist,
                                'album' => $currentItem->album,
                                'picture' => $currentItem->icon,
                                'link' => web($currentItem->shortUrl),
                                'score' => round(1/($i/10+1), 2) );
            } elseif ($itemType == 'album') {
                $data[] = array('title' => null,
                                'artist' => $currentItem->artist,
                                'album' => $currentItem->name,
                                'picture' => $currentItem->icon,
                                'link' => web($currentItem->shortUrl),
                                'score' => round(1/($i/10+1), 2) );
            }
        }
        
        return $data;
    }
    
    public function lookupPermalink($permalink)
    {
        // http://www.rdio.com/#/artist/David_Myhr/album/Soundshine
        // http://www.rdio.com/#/artist/Crash_Test_Dummies/album/God_Shuffled_His_Feet/track/Mmm_Mmm_Mmm_Mmm/
        $REGEX_RDIO_TRACK = "/artist\/".$this->REGEX_FULLSTRING."\/album\/".$this->REGEX_FULLSTRING."\/track\/".$this->REGEX_FULLSTRING."$/";
        $REGEX_RDIO_ALBUM = "/artist\/".$this->REGEX_FULLSTRING."\/album\/".$this->REGEX_FULLSTRING."$/";
        $REGEX_RDIO_ARTIST = "/artist\/".$this->REGEX_FULLSTRING."$/";
        
        $track = null;
        $valid = false;
        
        if (preg_match($REGEX_RDIO_TRACK, $permalink, $match)) {
            $valid = true;
            // We modify the query
            $query = str_replace('_', '+', $match[1]."+".$match[3]);
        } elseif (preg_match($REGEX_RDIO_ALBUM, $permalink, $match)) {
            $valid = true;
            
            // We modify the query
            $query = str_replace('_', '+', $match[1]."+".$match[2]);
        } elseif (preg_match($REGEX_RDIO_ARTIST, $permalink, $match)) {
            $valid = true;
            
            // We modify the query
            $query = str_replace('_', '+', $match[1]);
        }
            
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
        
    }

    // Playlists functions
    public function retrievePlaylist($permalink)
    {
        // http://www.rdio.com/people/tchap/playlists/955374/cyril/
        $REGEX_RDIO_PLAYLIST = "/rdio\.com\/people\/[^\/]*\/playlists\/([0-9]*)\/(.*)$/";
        
        if (!preg_match($REGEX_RDIO_PLAYLIST, $permalink, $match)) {
            return null;
        }

        $call = array("url" => $this->api_endpoint,
                      "data" => array('method' => "getObjectFromUrl", 'url' => $permalink, 'extras' => "tracks") ,
                      "method" => "POST");
      
        $result = $this->makeCall($call, $this->needsOAuth);

        if ($result == null) {
            return null;
        }
  
        $length = count($result->result->tracks);
        
        $data = null;
        // Normalizing each track found
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->result->tracks[$i];

            $data[] = array('seq' => $i,
                            'title' => $currentItem->name,
                            'artist' => $currentItem->artist,
                          'album' => $currentItem->album);
        }
        
        return array('title' => $result->name, 'count' => count($data),'tracks' => $data);
    }
    
    public function createPlaylist($title, $accessToken)
    {
        // FIX ME
        return false;
    }
}


/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                              QOBUZ                                                     */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class QOBUZ extends Platform
{

    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Qobuz";
        $this->safe_name = "_QOBUZ";
        $this->type = _LISTEN;
        $this->color = "2C8FAE";
        $this->id = $id;

        $this->api_key = $key;
        $this->needsOAuth = false;
        $this->api_secret = $secret;
        
        $this->api_endpoint = "http://www.qobuz.com/api.json/0.2/";
        $this->api_method = "GET";
        
        $this->query_endpoint = "track/search";
        $this->query_term = "query";
        $this->query_options = array("limit" => _LIMIT, "app_id" => $this->api_key);
        
        $this->query_album_endpoint = "album/search";
        $this->query_album_term = $this->query_term;
        $this->query_album_options = $this->query_options;
        
        $this->lookup_endpoint = "%s/get";
        $this->lookup_term = "%s_id";
        $this->lookup_options = $this->query_options;

        $this->track_permalink = "http://player.qobuz.com/#!/track/%s";
        $this->album_permalink = "http://player.qobuz.com/#!/album/%s";
        
        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
    
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "qobuz.") !== false);
    }

    public function getNormalizedResults($itemType, $query, $limit)
    {
        $result = $this->callPlatform($itemType, $query);
        if ($result == null) {
            return null;
        }

        $data = null;
        
        if ($itemType == 'track') {
            if (intval($result->tracks->total) != 0) {
                $object = $result->tracks->items;
            } else {
                return null;
            }
        } elseif ($itemType == 'album') {
            if (intval($result->albums->total) != 0) {
                $object = $result->albums->items;
            } else {
                return null;
            }
        }

        $length = min(count($object), $limit);

        for ($i=0; $i<$length; $i++) {
            if ($itemType == 'track') {
                $currentItem = $object[$i];

                if (isset($currentItem->performer)) {
                    $artist = $currentItem->performer->name;
                } else {
                    $artist = $currentItem->album->artist->name;
                }

                $data[] = array('title' => $currentItem->title,
                                'artist' => $artist,
                                'album' => $currentItem->album->title,
                                'picture' => $currentItem->album->image->small,
                                'link' => web(sprintf($this->track_permalink, $currentItem->id)),
                                'score' => round(1/($i/10+1), 2) );
            } elseif ($itemType == 'album') {
                $currentItem = $object[$i];
                $data[] = array('title' => null,
                                'artist' => $currentItem->artist->name,
                                'album' => $currentItem->title,
                                'picture' => $currentItem->image->small,
                                'link' => web(sprintf($this->album_permalink, $currentItem->id)),
                                'score' => round(1/($i/10+1), 2) );
            }
        }
        
        return $data;
    }
    
    public function lookupPermalink($permalink)
    {
    
        // http://www.qobuz.com/album/kitsune-maison-compilation-11-the-indie-dance-issue-various-artists/3700656500011
        $REGEX_QOBUZ_ALBUM_1 = "/album\/".$this->REGEX_FULLSTRING."\/([0-9]*)$/";
        
        // http://www.qobuz.com/telechargement-album-mp3/Various-Artists-Kitsune-Maison-Compilation-9-Petit-Bateau-Edition/Electronics-Electro/Interpretes-Divers/Kitsune/default/fiche_produit/id_produit-3760192210041.html?qref=sre_1_1
        $REGEX_QOBUZ_ALBUM_2 = "/\-([0-9]*)\.html\??.*$/";
        
        // http://player.qobuz.com/#!/album/0888880711410
        $REGEX_QOBUZ_ALBUM_3 = "/\!\/album\/([0-9]*)$/";
        
        // http://player.qobuz.com/#!/track/3551502
        $REGEX_QOBUZ_TRACK = "/\!\/track\/([0-9]*)$/";
       
        $track = null;
        $valid = false;

        if (preg_match($REGEX_QOBUZ_TRACK, $permalink, $match)) {
            $this->lookup_endpoint = 'track/get';
            $this->lookup_term = "track_id";
            $result = $this->callPlatform("lookup", $match[1]);
            if ($result == null) {
                return null;
            }
            
            if (isset($result->performer)) {
                $artist = $result->performer->name;
            } else {
                $artist = $result->album->artist->name;
            }

            // We encode the track to pass it on as the return track
            $track = array('name' => $result->title,
                           'artist' => $artist,
                           'album' => $result->album->title,
                           'picture' => $result->album->image->small,
                           'link' => web(sprintf($this->track_permalink, $result->id)) );
              
            // We modify the query
            $valid = true;
            $query = $track['artist']."+".$track['name'];
        } elseif (preg_match($REGEX_QOBUZ_ALBUM_3, $permalink, $match) || preg_match($REGEX_QOBUZ_ALBUM_2, $permalink, $match)) {
            $this->lookup_endpoint = 'album/get';
            $this->lookup_term = "album_id";
            $result = $this->callPlatform("lookup", $match[1]);
            if ($result == null) {
                return null;
            }

            // We encode the track to pass it on as the return track
            $track = array('name' => null,
                           'artist' => $result->artist->name,
                           'album' => $result->title,
                           'picture' => $result->image->small,
                           'link' => web(sprintf($this->album_permalink, $result->id)) );
              
            // We modify the query
            $valid = true;
            $query = $track['artist']."+".$track['album'];
        } elseif (preg_match($REGEX_QOBUZ_ALBUM_1, $permalink, $match)) {
            $this->lookup_endpoint = 'album/get';
            $this->lookup_term = "album_id";
            $result = $this->callPlatform("lookup", $match[2]);
            if ($result == null) {
                return null;
            }

            // We encode the track to pass it on as the return track
            $track = array('name' => null,
                           'artist' => $result->artist->name,
                           'album' => $result->title,
                           'picture' => $result->image->small,
                           'link' => web(sprintf($this->album_permalink, $result->id)) );
              
            // We modify the query
            $valid = true;
            $query = $track['artist']."+".$track['album'];
        }
      
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
    }

    public function signIn($userName, $hashedPassword)
    {
        $retour = $this->makeCall(
            array(
                'url' => $this->api_endpoint."user/login",
                'data' => array("app_id" => $this->api_key, "username" => $userName, "password" => $hashedPassword),
                'method' => $this->api_method
            ),
            $this->needsOAuth
        );

        if ($retour->user_auth_token) {
            return array( "access_token" => $retour->user_auth_token);
        } else {
            return null;
        }
    }

    // Playlists functions
    public function retrievePlaylist($permalink)
    {
        // http://player.qobuz.com/#!/playlist/157969
        $REGEX_QOBUZ_PLAYLIST = "/player\.qobuz\.com\/\#\!\/playlist\/([0-9]*)$/";
        
        if (!preg_match($REGEX_QOBUZ_PLAYLIST, $permalink, $match)) {
            return null;
        }

        $call = array("url" => $this->api_endpoint."playlist/get",
                      "data" => array('limit' => "999999", 'playlist_id' => $match[1], 'app_id' => $this->api_key, 'extra' => "tracks") ,
                      "method" => "GET");
      
        $result = $this->makeCall($call, $this->needsOAuth);

        if ($result == null) {
            return null;
        }
        
        $length = count($result->tracks->items);
        
        $data = null;
        // Normalizing each track found
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->tracks->items[$i];

            $data[] = array('seq' => $i,
                            'title' => $currentItem->title,
                            'artist' => $currentItem->performer->name,
                            'album' => $currentItem->album->title);
        }
        
        return array('title' => $result->name, 'count' => count($data),'tracks' => $data);
    }
  
    public function createPlaylist($title, $accessToken)
    {
        // FIX ME
        return false;
    }
}


/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                               XBOX                                                     */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class XBOX extends Platform
{

    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Xbox Music";
        $this->safe_name = "_XBOX";
        $this->type = _LISTEN;
        $this->color = "007500";
        $this->id = $id;

        $this->api_key = $key;
        $this->needsOAuth = false;
        $this->api_secret = $secret;
        
        $this->api_endpoint = "https://music.xboxlive.com/1/content/";
        $this->api_method = "GET";
        
        $this->query_endpoint = "music/search";
        $this->query_term = "q";
        $this->query_options = array("contentType" => "json", "accessToken" => "Bearer %s");
        
        $this->query_album_endpoint = $this->query_endpoint;
        $this->query_album_term = $this->query_term;
        $this->query_album_options = $this->query_options;
        
        $this->lookup_endpoint = "music.%s/lookup";
        $this->lookup_term = "";
        $this->lookup_options = $this->query_options;

        $this->track_permalink = "http://music.microsoft.com/Track/%s";
        $this->album_permalink = "http://music.microsoft.com/Album/%s";
        
        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
  
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "music.xbox.") !== false || strpos($permalink, "music.microsoft.") !== false);
    }

    protected function auth()
    {
        // From the XBOX docs : http://msdn.microsoft.com/en-us/library/dn546688.aspx

        $serviceauth = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13";
        $scope = "http://music.xboxlive.com";
        $grantType = "client_credentials";

        $requestData = array("client_id" => $this->api_key, "client_secret" => $this->api_secret, "scope" => $scope, "grant_type" => $grantType);
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($requestData),
            ),
        );
        $context  = stream_context_create($options);
        $response = json_decode(@file_get_contents($serviceauth, false, $context), true);
        $token = $response['access_token'];

        return $token;
    }

    public function getNormalizedResults($itemType, $query, $limit)
    {
        // Update token
        $token = $this->auth();
        $this->query_options["accessToken"] = sprintf($this->query_options["accessToken"], $token);
        $this->query_album_options["accessToken"] = sprintf($this->query_album_options["accessToken"], $token);

        $result = $this->callPlatform($itemType, $query);

        if ($result == null) {
            return null;
        }

        $data = null;
        
        if ($itemType == 'track' && property_exists($result, 'Tracks')) {
            if (count($result->Tracks->Items) != 0) {
                $object = $result->Tracks->Items;
            } else {
                return null;
            }
        } elseif ($itemType == 'album' && property_exists($result, 'Albums')) {
            if (count($result->Albums->Items) != 0) {
                $object = $result->Albums->Items;
            } else {
                return null;
            }
        } else {
            return null;
        }

        $length = min(count($object), $limit);

        for ($i=0; $i<$length; $i++) {
            if ($itemType == 'track') {
                $currentItem = $object[$i];
                $data[] = array('title' => $currentItem->Name,
                                'artist' => $currentItem->Artists[0]->Artist->Name,
                                'album' => $currentItem->Album->Name,
                                'picture' => $currentItem->ImageUrl,
                                'link' => web($currentItem->Link . "&action=play"),
                                'score' => round(1/($i/10+1), 2));
            } elseif ($itemType == 'album') {
                $currentItem = $object[$i];
                $data[] = array('title' => null,
                                'artist' => $currentItem->Artists[0]->Artist->Name,
                                'album' => $currentItem->Name,
                                'picture' => $currentItem->ImageUrl,
                                'link' => web($currentItem->Link . "&action=play"),
                                'score' => round(1/($i/10+1), 2));
            }
        }

        return $data;
    }
  
    public function lookupPermalink($permalink)
    {
        // http://music.xbox.com/Album/C954F807-0100-11DB-89CA-0019B92A3933
        $REGEX_XBOX_ALBUM = "/Album\/".$this->REGEX_FULLSTRING.".*$/";
        
        // http://music.xbox.com/Track/87CF3706-0100-11DB-89CA-0019B92A3933
        $REGEX_XBOX_TRACK = "/Track\/".$this->REGEX_FULLSTRING.".*$/";
       
        $track = null;
        $valid = false;

        // Update token
        $token = $this->auth();
        $this->lookup_options["accessToken"] = sprintf($this->lookup_options["accessToken"], $token);

        if (preg_match($REGEX_XBOX_TRACK, $permalink, $match)) {
            $this->lookup_endpoint = sprintf($this->lookup_endpoint, $match[1]);
            $result = $this->callPlatform("lookup", $match[1]);

            if ($result !== null && isset($result->Tracks) && isset($result->Tracks->Items) && count($result->Tracks->Items) > 0) {
                $object = $result->Tracks->Items[0];
            } else {
                return null;
            }

            // We encode the track to pass it on as the return track
            $track = array('name' => $object->Name,
                           'artist' => $object->Artists[0]->Artist->Name,
                           'album' => $object->Album->Name,
                           'picture' => $object->ImageUrl,
                           'link' => web($object->Link) );
              
            // We modify the query
            $valid = true;
            $query = $track['artist']."+".$track['name'];
        } elseif (preg_match($REGEX_XBOX_ALBUM, $permalink, $match)) {
            $this->lookup_endpoint = sprintf($this->lookup_endpoint, $match[1]);
            $result = $this->callPlatform("lookup", $match[1]);

            if ($result !== null && isset($result->Albums) && isset($result->Albums->Items) && count($result->Albums->Items) > 0) {
                $object = $result->Albums->Items[0];
            } else {
                return null;
            }

            // We encode the track to pass it on as the return track
            $track = array('name' => null,
                           'artist' => $object->Artists[0]->Artist->Name,
                           'album' => $object->Name,
                           'picture' => $object->ImageUrl,
                           'link' => web($object->Link) );
              
            // We modify the query
            $valid = true;
            $query = $track['artist']."+".$track['album'];
        }
      
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
          
    }
}


/* ****************************************************************************************************** */
/*                                                                                                        */
/*                                                                                                        */
/*                                          BEATS MUSIC                                                   */
/*                                                                                                        */
/*                                                                                                        */
/* ****************************************************************************************************** */
class BEATSMUSIC extends Platform
{

    public function __construct($key, $secret, $default, $activeSearch, $activeAlbumSearch, $activeLookup, $id)
    {
        $this->name = "Beats Music";
        $this->safe_name = "_BEATS";
        $this->type = _LISTEN;
        $this->color = "E31937";
        $this->id = $id;
        
        $this->api_key = $key;
        $this->api_endpoint = "https://partner.api.beatsmusic.com/v1/";
        $this->api_method = "GET";
        
        $this->query_endpoint = "api/search";
        $this->query_term = 'q';
        $this->query_options = array( 'type' => "track", "limit" => _LIMIT, 'client_id' => $this->api_key );
        
        $this->query_album_endpoint = $this->query_endpoint;
        $this->query_album_term = $this->query_term;
        $this->query_album_options = array( 'type' => "album", "limit" => _LIMIT, 'client_id' => $this->api_key );
        
        $this->lookup_endpoint = "api/%s/%s";
        $this->lookup_term = null;
        $this->lookup_options = array( 'client_id' => $this->api_key );

        $this->track_permalink = "http://on.beatsmusic.com/albums/%s/tracks/%s";
        $this->album_permalink = "http://on.beatsmusic.com/albums/%s";
        
        // Search and lookup Behavior
        $this->isDefault = $default;
        $this->isActiveForSearch = $activeSearch;
        $this->isActiveForAlbumSearch = $activeAlbumSearch;
        $this->isActiveForLookup = $activeLookup;
    }
    
    public function hasPermalink($permalink)
    {
        return (strpos($permalink, "beatsmusic.com") !== false);
    }

    public function getNormalizedResults($itemType, $query, $limit)
    {

        $result = $this->callPlatform($itemType, $query);
        if ($result == null || !isset($result->data)) {
            return null;
        }

        $length = min(count($result->data), $limit);
        
        $data = null;
        // Normalizing each track found
        for ($i=0; $i<$length; $i++) {
            $currentItem = $result->data[$i];

            if ($itemType == 'track') { // Track
              
                $data[] = array('title' => $currentItem->display,
                                'artist' => $currentItem->detail,
                                'album' => $currentItem->related->display,
                                'picture' => null,
                                'link' => web(sprintf($this->track_permalink, $currentItem->related->id, $currentItem->id)),
                                'score' => round(1/($i/10+1), 2));
            } elseif ($itemType == 'album') { // Album

                $data[] = array('title' => null,
                                'artist' => $currentItem->detail,
                                'album' => $currentItem->display,
                                'picture' => null,
                                'link' => web(sprintf($this->album_permalink, $currentItem->id)),
                                'score' => round(1/($i/10+1), 2));
            }
        }
        
        return $data;
    }
    
    public function lookupPermalink($permalink)
    {
      
        // http://on.beatsmusic.com/albums/al8992411/tracks/tr8992441
        // http://on.beatsmusic.com/artists/ar27304
        // http://on.beatsmusic.com/albums/al6960443
        $REGEX_BEATS_TRACK = "/albums\/al([0-9]*)\/tracks\/tr([0-9]*)$/";
        $REGEX_BEATS_ALBUM = "/albums\/al([0-9]*)$/";
        $REGEX_BEATS_ARTIST = "/artists\/ar([0-9]*)$/";
        
        $track = null;
        $valid = false;

        if (preg_match($REGEX_BEATS_TRACK, $permalink, $match)) {
            $this->lookup_endpoint = 'api/tracks/tr%s';
            $result = $this->callPlatform("lookup", $match[2]);
            if ($result == null || !isset($result->data)) {
                return null;
            }
          
            // We encode the track to pass it on as the return track
            $track = array('name' => $result->data->title,
                           'artist' => $result->data->artist_display_name,
                           'album' => $result->data->refs->album->display,
                           'picture' => null,
                           'link' => web(sprintf($this->track_permalink, $match[1], $match[2])) );
              
            // We modify the query
            $valid = true;
            $query = $track['album']."+".$track['name'];
        } elseif (preg_match($REGEX_BEATS_ALBUM, $permalink, $match)) {
            $this->lookup_endpoint = 'api/albums/al%s';
            $result = $this->callPlatform("lookup", $match[1]);
            if ($result == null || !isset($result->data)) {
                return null;
            }
          
            // We encode the track to pass it on as the return track
            $track = array('name' => null,
                           'artist' => $result->data->refs->artists[0]->display,
                           'album' => $result->data->title,
                           'picture' => null,
                           'link' => web(sprintf($this->album_permalink, $result->data->id)) );
              
            // We modify the query
            $valid = true;
            $query = $track['artist']."+".$track['album'];
        } elseif (preg_match($REGEX_BEATS_ARTIST, $permalink, $match)) {
            $this->lookup_endpoint = 'api/artists/ar%s';
            $result = $this->callPlatform("lookup", $match[1]);
            if ($result == null || !isset($result->data)) {
                return null;
            }
          
            // No tracks to encode
            // We modify the query
            $valid = true;
            $query = $result->data->name;
        }
      
        if ($valid) {
            return array('query' => $query, 'track' => $track);
        } else {
            return null;
        }
    }
}
