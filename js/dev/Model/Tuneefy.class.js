var MAX_API_CALLS_PER_PERIOD = 2; // 250e sec

function Tuneefy(initialController) {

  this.controller = initialController;

  /** Query and platforms **/
  this.query = "";
  this.itemType = 0; // 0 == track , 1 = album
  this.limit = 100;
  this.platforms = "";
  this.platformCount = 0;
  /** Added item if any **/
  this.lookedUpPlatform = -1;
  this.lookedUpItem = null;
  /** Results **/
  this.results = [];
  this.playlistName = "";
  this.trackCount = 0;
  this.strict = false;
  
  this.bindObjects();
  
};

Tuneefy.prototype.bindObjects = function() {

  /******* SEARCH START *******/
  $(this.controller).bind("tuneefy.search.start", $.proxy(function(e, query, itemType, strict, platforms, limit){
    
    console.log('   Tuneefy <<< search.start for type : ' + itemType );
    
    this.results = []; // reset
    this.itemType = itemType;
    this.query = decodeURIComponent(query);
    this.strict = strict;
    this.limit = limit;
    this.platforms = platforms.split(',');
    this.platformCount = this.platforms.length;

    this.runQuery();
    
  }, this));
  
  /******* PLAYLIST RETRIEVAL START *******/
  $(this.controller).bind("tuneefy.retrieve.playlist", $.proxy(function(e, platform, query){
    
    console.log('   Tuneefy <<< retrieve.playlist');
    
    this.platforms = platform;
    this.results = []; // reset
    this.query = decodeURIComponent(query);
    
    this.getPlaylistData();
    
  }, this));

};

Tuneefy.prototype.runQuery = function(){

  // Actual search
  $.get("/include/share/query.php", {str: this.query}, $.proxy(function(data){

    console.log('[[Query = ' + this.query + ' returned]]');
    
    // Calling the callback to launch search
    if (data != null) {

      this.query = data.data.query.transformed;
      this.lookedUpPlatform = data.data.lookedUpPlatform;
      this.lookedUpItem = data.data.lookedUpItem;
      
      console.log('Tuneefy >>> query.updated with ' + this.query);
      $(this.controller).trigger("tuneefy.query.updated", [this.query]);
      this.runGets();
      
    } else {
    
      console.log('Tuneefy >>> alert.invalidQuery');
      $(this.controller).trigger("tuneefy.alert.invalidQuery");
      
    }
    
  }, this));

};

Tuneefy.prototype.runGets = function(){
  
  /* Adding the track if there is any */
  if (this.lookedUpPlatform != null && this.lookedUpPlatform != -1 && $.inArray(this.lookedUpPlatform+'',_all_platforms) != -1) {

    // We force the search on this platform, else it's biased
    if ($.inArray(this.lookedUpPlatform+'',this.platforms) == -1) {
      this.platforms.pushlookedUpPlatform;
      this.platformCount++;
    }
    
    if (this.lookedUpItem != null) {
    
      // Then we create a new entry in result, with a big score to pop it first
      var links = new Links();
      links.addLink(this.lookedUpPlatform,this.lookedUpItem.link);
      
      var currentItem = new Item(this.itemType,
                          this.lookedUpItem.artist, 
                          this.lookedUpItem.name,  
                          this.lookedUpItem.album, 
                          this.lookedUpItem.picture,
                          links,
                          10
                          );
                          
      currentItem.addToItems(this.results, this.strict);
    }
    
  }
   
  /* Lauching the GETs */
  // For each platform, we perform the getJSON
  $.each(this.platforms,$.proxy(function(index,pltf) {
      
      $.getJSON("/include/share/get.php", {id: pltf, query: this.query, itemType: this.itemType, limit: this.limit},$.proxy(function(data, status){
        
        if (data != null ) { 
          this.parseDataFromPlatform(pltf, data.data);
        } else {
          this.parseDataFromPlatform(pltf, null);
        }
        
      }, this));

  }, this));
 
};

Tuneefy.prototype.parseDataFromPlatform = function(type, raw){
  
  if (raw == '0') {
  
    console.log('Tuneefy >>> alert.platformReturnedNoResult for platform #' + type);
    $(this.controller).trigger("tuneefy.alert.platformReturnedNoResult", type);

  } else if (raw == null) {
  
    console.log('Tuneefy >>> alert.apiSeemsDown for platform #' + type);
    $(this.controller).trigger("tuneefy.alert.apiSeemsDown", type); 
  
  } else if (raw == '-42') {
  
    console.log('Tuneefy >>> platform #' + type + ' is not active for search');
    
  } else {

    var currentItem = null, currentRawItem = null;
    var links;
  
    var length = raw.length;
    
    for (var i=0;i<length; i++){
  
      currentRawItem = raw[i];
  
      links = new Links(); // We need to keep this so as to keep object methods in Links
      links.addLink(type,currentRawItem.link);
  
      currentItem = new Item(this.itemType,
                          currentRawItem.artist, 
                          currentRawItem.title,  
                          currentRawItem.album, 
                          currentRawItem.picture,
                          links,
                          currentRawItem.score
                          );
                          
      currentItem.addToItems(this.results, this.strict);
  
    }
  
    console.log('Tuneefy >>> search.newResultsReady for platform #' + type);
    $(this.controller).trigger("tuneefy.search.newResultsReady", this.query, type);
  
  }
  
  this.platformCount--;
  
  if (this.platformCount === 0) {
  
    if (this.results.length == 0) {
      console.log('Tuneefy >>> alert.noResultsFound');
      $(this.controller).trigger("tuneefy.alert.noResultsFound");
    }
    
    console.log('Tuneefy >>> search.finished');
    $(this.controller).trigger("tuneefy.search.finished");
    
  }
  
};


Tuneefy.prototype.getPlaylistData = function(){

  $.getJSON("/include/share/getPlaylist.php", {str: this.query},$.proxy(function(data, status){
          
    this.playlistName = data.data.title;
    this.trackCount = data.data.count;
    this.results = data.data.tracks;
      
    console.log('Tuneefy >>> retrieve.finished');
    $(this.controller).trigger("tuneefy.retrieve.finished");
 
    this.findPlaylistEquivalents(0);
 
  }, this));

};


Tuneefy.prototype.findPlaylistEquivalents = function(offset){

  var limit = Math.min(this.results.length, offset + MAX_API_CALLS_PER_PERIOD);
  
  /* Lauching the GETs */
  // For each track, we perform the getJSON
  for (var i=offset;i<limit; i++){
    
    $.getJSON("/include/share/get.php", {id: this.platforms, query: this.results[i].title + " " + this.results[i].artist, itemType: 0, limit: 1, json_key: i},$.proxy(function(data, status){
      
      if (data.data[0] != null)
        this.results[data.json_key].link = data.data[0].link;
      
      this.trackCount--;
      
      if (this.trackCount === 0 ) {        
        console.log('Tuneefy >>> process.finished');
        $(this.controller).trigger("tuneefy.process.finished");
        return;
      }
      
    }, this));


  }
  
  if (offset + MAX_API_CALLS_PER_PERIOD >= this.results.length) return ;
  
  console.log('Tuneefy >>> retrieve.finished');
  $(this.controller).trigger("tuneefy.retrieve.finished");
        
  // So as not to hit the limit of the APIs
  console.log("Tuneefy >>> contacting API and timing out");
  window.setTimeout($.proxy(function(){ this.findPlaylistEquivalents(offset + MAX_API_CALLS_PER_PERIOD);}, this),250);   

};
