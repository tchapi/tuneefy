function flatten(str)
{
 var rExps=[
 {re:/[\xE0-\xE6]/g, ch:'a'},
 {re:/[\xE8-\xEB]/g, ch:'e'},
 {re:/[\xEC-\xEF]/g, ch:'i'},
 {re:/[\xF2-\xF6]/g, ch:'o'},
 {re:/[\xF9-\xFC]/g, ch:'u'},
 {re:/[\xF1]/g, ch:'n'} ];

 for(var i=0, len=rExps.length; i<len; i++)
  str=str.replace(rExps[i].re, rExps[i].ch);

 return str;
};

/* 
 * Constructor Function
 */
function Item(itemType, artist, name, album, image, links, score) {

  this.type = itemType;
  this.artist = artist;
  this.name = name;
  this.album = album;
  this.image = image;
  this.links = links;

  this.score = score;
  this.span = 1;

};

/* 
 * Hash Function
 */
Item.prototype.hash = function(strictMode){

  if (this.type == 0) { // Track

    // Strip "(Album Version)" from track names
    var regex_album_version = /^(.*)(\(|\[)(\s*)(album\sversion|version\salbum)([^\)^\]]*)(\)|\])(.*)$/gi;
    var text = this.name

    if (regex_album_version.test(text)) {
      text = text.replace(regex_album_version, "$1").replace(/\s\-$/,'') + text.replace(regex_album_version, "$7");
    }

    // Find "feats"
    var regex_feat_p = /^(.*)(\(|\[)(\s*)(feat\s|feat\.|featuring\s|ft.|ft\s)([^\)^\]]*)(\)|\])(.*)$/gi;
    var regex_feat_s = /^(.*)\s(feat\s|feat\.|featuring\s|ft.|ft\s)([^\(^\[^\]^\)]*)(.*)$/gi;
    var feat = "";

    if (regex_feat_p.test(text)) {
      feat = text.replace(regex_feat_p, "$5");
      text = text.replace(regex_feat_p, "$1").replace(/\s\-$/,'') + text.replace(regex_feat_p, "$7");
    } else if (regex_feat_s.test(text)) {
      feat = text.replace(regex_feat_s, "$3");
      text = text.replace(regex_feat_s, "$1").replace(/\s\-$/,'') + text.replace(regex_feat_s, "$4");
    }
  
    return flatten((this.artist + '' + text + feat + (strictMode?'':this.album)).split(/[ ,;\-]/g).join('').toLowerCase());

  } else if (this.type == 1) { // Album

    return flatten((this.artist + '' + this.album).split(/[ ,;\-]/g).join('').toLowerCase());

  }

}; 

/* 
 * Compare Function
 */
Item.prototype.equals = function(anotherItem, strictMode){
  return this.hash(strictMode) == anotherItem.hash(strictMode);
};

/* 
 * AddToItems Function
 */
Item.prototype.addToItems = function(items, strictMode){
    
  // We keep the length somewhere safe in case items gets modified asynchronously
  var depth = items.length;
  
  // For each item already given
  for(var i = 0; i < depth; i++){
  
      var currentItem = items[i];
      
      if (this.equals(currentItem, strictMode)) {
        // We have found a duplicate, let's add a link
        currentItem.links.merge(this.links);
        // And let's improve its cover image (we don't like deezer & youtube for this)
        if (currentItem.image == null && this.image != null && $.inArray("deezer",this.image) == -1 && $.inArray("ytimg",this.image) == -1) {
          // Then let's change image
          currentItem.image = this.image;
          if (this.album != null) currentItem.album = this.album;
        }
        if (currentItem.album == null && this.album != null) {
          // Then let's change the album
          currentItem.album = this.album;
        }
        
        // Let's add popularities
        currentItem.score += this.score;
        // And let's update the span (number of different platforms) :
        currentItem.span = currentItem.links.getSpan();
        
        return;
      }
      
  }
  
  // If no duplicate was found, we add the item to the list
  items.push(this);
    
};