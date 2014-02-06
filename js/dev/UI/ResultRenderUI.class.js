/*global $,console,$p,$table_link_prefix, $results_found,$back_to_top,$various_albums,$header_track,$header_artist,$featuring,$header_album,$tracks,$albums,$playlists_results_found,$header_available,$listen_to,$share,$share_tip*/
/*jslint browser: true*/
var ResultRenderUI = function(initialModel, initialController, resultsPerPage, pages, widget) {

    this.model = initialModel;
    this.controller = initialController;

    this.resultsDiv = $("#results");
    this.waitingDiv = $("#waiting");
    this.paginators = $("a.tPagerNext img, a.tPagerPrev img, .tPagerPage");
    this.initialQuery = "";

    /* item type */
    this.itemType = 0; // Gives a valid value for the widget = always tracks

    /* Paging */
    this.maxResultsPerPage = resultsPerPage;
    this.maxPages = pages;

    /* Widget or not */
    this.widget = widget;

    this.initUIInitialState();
    this.bindObjects();

};

ResultRenderUI.prototype.initUIInitialState = function() {

    // Pagination buttons
    this.paginators.live("click", $.proxy(function(event) {

        var offset = $(event.target).attr("rel");
        if (offset === null) {
            offset = $(event.target).parent().attr("rel");
        }

        if (offset !== "" && offset !== null) {
            this.display(Math.max(offset, 0));
        }

    }, this));

    $(".backToTop").live("click", function(e) {
        e.preventDefault();
        $("html,body").animate({
            scrollTop: 0
        }, 1500);
    });

    // Only if it is not the widget **************
    if (!this.widget) {
        // The SHARE button triggers an event
        $(".sharePage").live("click", $.proxy(function(event) {

            // The REL attribute has the id of the track in the result array
            this.getShareLink($(event.target).attr("rel") - 1);

        }, this));
    }
    // ***********************

};

ResultRenderUI.prototype.bindObjects = function() {

    /******* SEARCH LAUNCHED *******/
    $(this.controller).bind("tuneefy.search.launched", $.proxy(function(e, itemType) {

        console.log('   ResultRenderUI <<< search.launched');
        this.resultsDiv.empty();
        this.waitingDiv.show();

        // We set the itemType now 
        this.itemType = itemType;

    }, this));

    /******* RESULTS READY *******/
    $(this.controller).bind("tuneefy.search.newResultsReady", $.proxy(function(e, initialQuery) {

        console.log('   ResultRenderUI <<< search.newResultsReady');
        this.initialQuery = initialQuery;
        this.display(0);

    }, this));

    /******* SEARCH FINISHED *******/
    $(this.controller).bind("tuneefy.search.finished", $.proxy(function() {

        console.log('   ResultRenderUI <<< search.finished');
        this.waitingDiv.hide();

        $('html,body').animate({
            scrollTop: this.resultsDiv.offset().top - 20
        }, "slow");

        // In case we're the widget
        if (this.widget) {
            // We set the href. No event is bound to the click, so the natural click will occur
            this.getShareLink(0);
            $(".sharePage").attr("target", "_blank");

        }

    }, this));

    /******* RETRIEVE PLAYLIST LAUNCHED *******/
    $(this.controller).bind("tuneefy.retrieve.playlist", $.proxy(function() {

        console.log('   ResultRenderUI <<< retrieve.playlist');
        this.resultsDiv.empty();
        this.waitingDiv.show();

    }, this));

    /******* RETRIEVE PLAYLIST FINISHED *******/
    $(this.controller).bind("tuneefy.retrieve.finished", $.proxy(function() {

        console.log('   ResultRenderUI <<< retrieve.finished');
        this.displayPlaylist();

    }, this));

    /******* PROCESS PLAYLIST FINISHED *******/
    $(this.controller).bind("tuneefy.process.finished", $.proxy(function() {

        console.log('   ResultRenderUI <<< process.finished');
        this.waitingDiv.hide();
        this.displayPlaylist();

        $('html,body').animate({
            scrollTop: this.resultsDiv.offset().top - 100
        }, "slow");

    }, this));


    /**** ON POP STATE ****/
    $(this.controller).bind("tuneefy.search.popstate", $.proxy(function(e, event) {

        console.log('   ResultRenderUI <<< search.popstate');

        if (event.state) {
            if (event.state.search === true) {

                // We revert back the history
                this.itemType = event.state.type;
                this.initialQuery = event.state.term;
                this.model.results = event.state.results;

                this.display(0);
                console.log('*Search state fetched from history *');

            }
        }
    }, this));

};

/* 
 * Get Share Link Function
 */
ResultRenderUI.prototype.getShareLink = function(id) {

    id = Math.max(0, id);

    var whatToShare = {
        itemType: this.itemType,
        name: this.model.results[id].name,
        artist: this.model.results[id].artist,
        album: this.model.results[id].album,
        image: this.model.results[id].image
    };

    $.each(this.model.results[id].links.links, $.proxy(function(pltf, link) {

        if (link !== null) {
            whatToShare["" + $table_link_prefix + pltf] = link;
        }

    }, this));

    $.post("/include/share/share.php", whatToShare, $.proxy(function(data) {
        // We send the request for a new share and redirect to the page afterwards,
        // or write the href into the button in case it's the widget

        if (this.widget) {

            $(".sharePage").attr("href", data);

        } else {
            // So we get History back with HTML5 when possible
            if (history.pushState) {
                history.pushState({
                    search: true,
                    type: this.itemType,
                    term: this.initialQuery,
                    results: this.model.results
                }, null);
                console.log('*Search state pushed into history *');
            }

            // And then we navigate to the page
            top.location.href = data;
        }

    }, this));
};

/* 
 * Display Function
 */
ResultRenderUI.prototype.display = function(offset) {

    this.model.results.sort(function(a, b) {
        // Calculates a global score
        return (b.span - a.span) * 100 + (b.score - a.score);
    });

    var totalResults = Math.min(this.model.results.length, this.maxResultsPerPage * this.maxPages),
        maxResults = Math.min(this.maxResultsPerPage + offset, totalResults),
        /* Number of results and back to top */
        $nb_results = $results_found;

    // If we have nothing to display, return
    if (maxResults === 0) {
        return;
    }

    if (this.itemType === 0) { // track
        $nb_results = $results_found.replace('#type#', $tracks);
    } else if (this.itemType === 1) { // album
        $nb_results = $results_found.replace('#type#', $albums);
    }

    var sumUp = this.makeDiv($nb_results.replace('#nb#', "<span class=\"color\">" + totalResults + "</span>")
        .replace('#iq#', "<span class=\"color\">" + this.initialQuery + "</span>")
        .replace(/\+/g, ' '), "nbResults", ""),

        backToTop = this.makeLink($back_to_top, "btn backToTop", ""),
        res = "<ul>",
        current = null;

    /* Header row */
    res += "<li class=\"tHeader\">";
    res += "<div class=\"tHeader_bf\">&nbsp;</div>";
    if (this.itemType === 0) { // track
        res += "<div class=\"tHeader_t\">" + $header_track + "</div>";
        res += "<div class=\"tHeader_a\">" + $header_artist + "</div>";
        res += "<div class=\"tHeader_c\">" + $header_album + "</div>";
    } else if (this.itemType === 1) { // album
        res += "<div class=\"tHeader_tA\">" + $header_album + "</div>";
        res += "<div class=\"tHeader_aA\">" + $header_artist + "</div>";
    }
    res += "<div class=\"tHeader_p\">" + $header_available + "</div>";
    res += "<div class=\"tHeader_af\">&nbsp;</div>";
    res += "</li>";


    /* ==== For EACH result in the max and min span ==== */
    var k = offset,
        tlinks = "",
        alternative = "";
    for (k = offset; k < maxResults; k += 1) {

        current = this.model.results[k];

        // We output the basic stuff about the track :
        res += "<li class=\"tResult\">";
        res += this.makeDiv(this.makeImg(current.image, 74, 74) + this.makeDiv("", "coverlay", ""), "tImage", "");
        res += this.cleanForDisplay(current.name, current.artist, current.album);

        tlinks = "";
        alternative = "";

        if (this.itemType === 0) {
            alternative = $listen_to.replace('#name#', current.name);
        } else if (this.itemType === 1) {
            alternative = $listen_to.replace('#name#', current.album);
        }
        // We have to print links intelligently
        $.each(current.links.links, $.proxy(function(pltf, link) {

            tlinks += this.makeListenLink("", "tLink btns btn_" + pltf, link, pltf, alternative.replace('#p#', $p[pltf]));

        }, this));

        res += this.makeDiv(this.makeDiv(tlinks, "wrapper", ""), "tLinks", "");

        // The SHARE link
        if (this.itemType === 0) {
            res += this.makeLink($share, "btn tShare sharePage", null, (k + 1), $share_tip.replace('#name#', current.name));
        } else if (this.itemType === 1) {
            res += this.makeLink($share, "btn tShare sharePage", null, (k + 1), $share_tip.replace('#name#', current.album));
        }
        res += "</li>";

    }

    res += "</ul>";

    /* ==== PAGER ==== */
    // Total number of pages :
    var totalPages = Math.min(this.maxPages, Math.round(totalResults / this.maxResultsPerPage)),
        pager = "";

    if (totalPages > 0) {

        if (offset > 0) {
            // More results before
            var newOffset = offset - this.maxResultsPerPage;
            pager += this.makeLink(this.makeImg("/img/pagination_left.png", 17, 17), "btn tPagerPrev", null, '' + newOffset);
        }

        var page = Math.round(offset / this.maxResultsPerPage) + 1,
            before = 2;
        for (before = 2; before > 0; before -= 1) {
            if ((page - before) > 0) {
                pager += this.makeLink(page - before, "btn tPagerPage", null, offset - this.maxResultsPerPage * before);
            }
        }

        if (totalPages !== 1) {
            pager += this.makeLink(page, "btn tPagerPage selected ins txtS2", "", "");
        }

        var after = 1;
        for (after = 1; after < 3; after += 1) {
            if ((page + after) < (totalPages + 1)) {
                pager += this.makeLink(page + after, "btn tPagerPage", null, offset + this.maxResultsPerPage * after);
            }
        }

        if (page * this.maxResultsPerPage < totalResults) {
            // More results ahead
            var newOffset = offset + this.maxResultsPerPage;
            pager += this.makeLink(this.makeImg("/img/pagination_right.png", 17, 17), "btn tPagerNext", null, '' + newOffset);
        }

        pager = this.makeDiv(pager, "pager", "");
    }

    /* ==== Actual display ==== */
    this.resultsDiv.html(this.makeDiv(sumUp + pager, "above", "") + res + this.makeDiv(backToTop + pager, "below", ""));

};


/* 
 * Display Playlist Function
 */
ResultRenderUI.prototype.displayPlaylist = function() {

    var totalResults = this.model.results.length,

        /* Number of results and back to top */
        $nb_results = $playlists_results_found.replace('#type#', $tracks),
        sumUp = this.makeDiv($nb_results.replace('#nb#', "<span class=\"color\">" + totalResults + "</span>")
            .replace('#iq#', "<span class=\"color\">" + this.model.playlistName + "</span>")
            .replace(/\+/g, ' '), "nbResults", ""),
        backToTop = this.makeLink($back_to_top, "btn backToTop", ""),
        res = "<ul>",
        current = null;

    /* Header row */
    res += "<li class=\"tHeader\">";
    res += "<div class=\"tHeader_t tPlaylist\">" + $header_track + "</div>";
    res += "<div class=\"tHeader_a\">" + $header_artist + "</div>";
    res += "<div class=\"tHeader_c\">" + $header_album + "</div>";

    res += "</li>";


    /* ==== For EACH result in the max and min span ==== */
    var k = 0,
        alternative;
    for (k = 0; k < totalResults; k += 1) {

        current = this.model.results[k];
        if (current === null) {
            break;
        }

        // We output the basic stuff about the track :
        if (current.link !== null) {
            res += "<li class=\"tResult tPlaylist ok\" rel=\"" + current.link + "\">";
        } else {
            res += "<li class=\"tResult tPlaylist\">";
        }

        res += this.cleanForDisplay(current.title, current.artist, current.album);

        alternative = $listen_to.replace('#name#', current.title);

        res += "</li>";

    }

    res += "</ul>";

    /* ==== Actual display ==== */
    this.resultsDiv.html(this.makeDiv(sumUp, "above", "") + res + this.makeDiv(backToTop, "below", ""));

};


/*
 * Helpers for displaying spans
 */
ResultRenderUI.prototype.makeDiv = function(content, oclass, rel, title) {

    if (rel === null) {
        rel = "";
    }

    var options = (oclass ? "class=\"" + oclass + "\" " : "") + (rel !== "" ? "rel=\"" + rel + "\" " : "") + (title ? "title=\"" + title + "\" " : ""),
        output = "<div " + options + ">";

    output += content;
    output += "</div>";

    return output;

};

ResultRenderUI.prototype.makeImg = function(src, width, height, alt) {

    var $alt_src = "/img/nothumb";
    if (this.itemType === 0) {
        $alt_src = $alt_src + "_track.png";
    }
    if (this.itemType === 1) {
        $alt_src = $alt_src + "_album.png";
    }
    return "<img src=\"" + (src || $alt_src) + "\" height=\"" + height + "\" width=\"" + width + "\" " + (alt ? "alt=\"" + alt + "\" " : "") + "/>";

};

ResultRenderUI.prototype.makeListenLink = function(content, oclass, uri, pltf, title) {

    if (uri) {

        var href = ("/include/share/listen.php?p=" + pltf + "&t=" + encodeURIComponent(uri));
        return this.makeLink(content, oclass, href, null, title);

    } else {

        return "";

    }

};

ResultRenderUI.prototype.makeLink = function(content, oclass, href, rel, title) {

    if (rel === null) {
        rel = "";
    }

    var options = (rel !== "" ? "rel=\"" + rel + "\" " : "") + (title ? "title=\"" + title + "\" " : "");
    options += (oclass ? "class=\"" + oclass + "\" " : "");

    var output = "<a " + (href ? "href=\"" + href + "\" " : "") + options + "target=\"_top\" >";
    output += content;
    output += "</a>";

    return output;

};

ResultRenderUI.prototype.cut = function(text, max) {

    text = $.trim(text);

    if (text.length < max) {
        return text;
    } else {
        return (text.substr(0, max - 3) + '...');
    }

};

ResultRenderUI.prototype.findFeat = function(text) {

    var regex_feat_p = /^(.*)(\(|\[)(\s*)(feat\s|feat\.|featuring\s|ft\.|ft\s)([^\)^\]]*)(\)|\])(.*)$/gi,
        regex_feat_s = /^(.*)\s(feat\s|feat\.|featuring\s|ft\.|ft\s)([^\(^\[^\]^\)]*)(.*)$/gi,
        feat = "";

    if (regex_feat_p.test(text)) {
        feat = text.replace(regex_feat_p, "$5");
        text = text.replace(regex_feat_p, "$1").replace(/\s\-$/, '') + text.replace(regex_feat_p, "$7");
    } else if (regex_feat_s.test(text)) {
        feat = text.replace(regex_feat_s, "$3");
        text = text.replace(regex_feat_s, "$1").replace(/\s\-$/, '') + text.replace(regex_feat_s, "$4");
    }

    return {
        text: text,
        feat: feat
    };
};

ResultRenderUI.prototype.cleanForDisplay = function(title, artist, album) {

    var feat = this.findFeat(album).feat,
        cleaned = "",
        album = this.findFeat(album).text,
        tmp_feat = this.findFeat(artist).feat;

    if (tmp_feat !== "") {
        feat = tmp_feat;
    }

    artist = this.findFeat(artist).text;

    tmp_feat = this.findFeat(title).feat;

    if (tmp_feat !== "") {
        feat = tmp_feat;
    }

    title = this.findFeat(title).text;

    if ($.trim(feat) === $.trim(artist)) {
        feat = "";
    }

    if (feat !== "") {
        feat = $featuring + feat;
    }

    if (this.itemType === 0) { //track

        cleaned += this.makeDiv(this.makeDiv(this.cut(title, 60) + this.makeDiv(this.cut(feat, 60), "tFeat txtS"), "wrapper"), "tTitle");
        cleaned += this.makeDiv(this.makeDiv(this.cut(artist, 60), "wrapper"), "tArtist txtS");
        cleaned += this.makeDiv(this.makeDiv((album ? this.cut(album, 60) : $various_albums), "wrapper"), "tAlbum txtS");

    } else if (this.itemType === 1) { //album

        cleaned += this.makeDiv(this.makeDiv(this.cut(album, 60) + this.makeDiv(this.cut(feat, 60), "tFeat txtS"), "wrapper"), "tAlbumA");
        cleaned += this.makeDiv(this.makeDiv(this.cut(artist, 60), "wrapper"), "tArtistA txtS");

    }

    return cleaned;

};