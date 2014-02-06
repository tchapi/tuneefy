/*global $,console,*/
/*jslint browser: true*/
var flatten = function(str) {
    var rExps = [{
        re: /[\xE0-\xE6]/g,
        ch: 'a'
    }, {
        re: /[\xE8-\xEB]/g,
        ch: 'e'
    }, {
        re: /[\xEC-\xEF]/g,
        ch: 'i'
    }, {
        re: /[\xF2-\xF6]/g,
        ch: 'o'
    }, {
        re: /[\xF9-\xFC]/g,
        ch: 'u'
    }, {
        re: /[\xF1]/g,
        ch: 'n'
    }];

    var i = 0,
        len = rExps.length;
    for (i = 0; i < len; i += 1) {
        str = str.replace(rExps[i].re, rExps[i].ch);
    }

    return str;
};

/* 
 * Constructor Function
 */
var Item = function(itemType, artist, name, album, image, links, score) {

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
Item.prototype.hash = function(strictMode) {

    var title = this.name,
        artist = this.artist,

        // Find "feats" in artist
        regex_feat_p = /^(.*)(\(|\[)(\s*)(feat\s|feat\.|featuring\s|ft\.|ft\s)([^\)^\]]*)(\)|\])(.*)$/gi,
        regex_feat_s = /^(.*)\s(feat\s|feat\.|featuring\s|ft\.|ft\s)([^\(^\[^\]^\)]*)(.*)$/gi,

        // Strip "(Album Version)" from track names
        regex_album_version = /^(.*)(\(|\[)(\s*)(album\sversion|version\salbum)([^\)^\]]*)(\)|\])(.*)$/gi;

    if (regex_feat_p.test(artist)) {
        artist = artist.replace(regex_feat_p, "$1").replace(/\s\-$/, '') + artist.replace(regex_feat_p, "$7");
    } else if (regex_feat_s.test(artist)) {
        artist = artist.replace(regex_feat_s, "$1").replace(/\s\-$/, '') + artist.replace(regex_feat_s, "$4");
    }

    if (this.type === 0) { // Track

        if (regex_album_version.test(title)) {
            title = title.replace(regex_album_version, "$1").replace(/\s\-$/, '') + title.replace(regex_album_version, "$7");
        }

        // Find "feats" in track name

        if (regex_feat_p.test(title)) {
            title = title.replace(regex_feat_p, "$1").replace(/\s\-$/, '') + title.replace(regex_feat_p, "$7");
        } else if (regex_feat_s.test(title)) {
            title = title.replace(regex_feat_s, "$1").replace(/\s\-$/, '') + title.replace(regex_feat_s, "$4");
        }

        return flatten((artist + '' + title + (strictMode ? '' : this.album)).split(/[ ,;\-]/g).join('').toLowerCase());

    } else if (this.type === 1) { // Album

        return flatten((artist + '' + this.album).split(/[ ,;\-]/g).join('').toLowerCase());

    }

};

/* 
 * Compare Function
 */
Item.prototype.equals = function(anotherItem, strictMode) {
    return this.hash(strictMode) === anotherItem.hash(strictMode);
};

/* 
 * AddToItems Function
 */
Item.prototype.addToItems = function(items, strictMode) {

    // We keep the length somewhere safe in case items gets modified asynchronously
    var depth = items.length,
        currentItem,
        i = 0;

    // For each item already given
    for (i = 0; i < depth; i += 1) {

        currentItem = items[i];

        if (this.equals(currentItem, strictMode)) {
            // We have found a duplicate, let's add a link
            currentItem.links.merge(this.links);
            // And let's improve its cover image (we don't like deezer & youtube for this)
            if (currentItem.image === null && this.image !== null && $.inArray("deezer", this.image) === -1 && $.inArray("ytimg", this.image) === -1) {
                // Then let's change image
                currentItem.image = this.image;
                if (this.album !== null) {
                    currentItem.album = this.album;
                }
            }
            if (currentItem.album === null && this.album !== null) {
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