/*global $,console,$yes,$no,$default_platforms,$query_label*/
/*jslint browser: true*/
var $COOKIE = "tuneefyPrefs";
var $COOKIE_2 = "tuneefyHelpBox";
var $COOKIE_3 = "tuneefySearchType";

var SearchUI = function(initialController) {

    this.controller = initialController;

    this.form = $("#find");
    this.queryField = $("#query");
    this.searchButton = $("#launch");
    this.strictModeCheckBox = $("#strictMode");
    this.searchTypeCheckBox = $("#searchTypeCheckbox");
    this.optionsButton = $("#options");
    this.advanced = $("#advanced,#hideMisere");

    this.platforms = $("a.btns");
    this.selectedPlatforms = ""; // By default

    this.searchForIt = $(".searchForIt");
    this.closeHelp = $("span.closeHelp");
    this.closeForever = $("span.closeForever");
    this.resetField = $("#resetQuery");

    this.initUIInitialState();
    this.bindObjects();

};

SearchUI.prototype.initUIInitialState = function() {

    // Re-enable the button in case and cleanse platforms checkBoxes
    this.searchButton.removeAttr('disabled');
    this.platforms.removeAttr('on');

    // Special iphone-like button for merge albums & tracks/albums switch
    if (typeof $.iphoneStyle === 'function') {

        // Merge Albums
        this.strictModeCheckBox.iphoneStyle({
            checkedLabel: $yes,
            uncheckedLabel: $no,
            resizeContainer: false,
            resizeHandle: false
        });

        // Tracks or Albums
        this.searchTypeCheckBox.iphoneStyle({
            checkedLabel: "",
            uncheckedLabel: "",
            resizeContainer: false,
            resizeHandle: false,
            containerClass: 'iPhoneCheckContainer otherContainer',
            labelOnClass: 'iPhoneCheckLabelOn albums',
            labelOffClass: 'iPhoneCheckLabelOff tracks',
            handleClass: 'iPhoneCheckHandle otherHandle',
            handleCenterClass: 'iPhoneCheckHandleCenter noBG',
            handleRightClass: 'iPhoneCheckHandleRight activeTracks',
            containerRadius: 2,
            onChange: $.proxy(function() {
                var value;
                if (this.searchTypeCheckBox.is(':checked')) {
                    // track 
                    $('#typeTracks').removeClass('off');
                    $('#typeAlbums').addClass('off');
                    $('.iPhoneCheckHandleRight.activeAlbums').addClass('activeTracks');
                    $('.iPhoneCheckHandleRight.activeAlbums').removeClass('activeAlbums');

                    // Cookie_3
                    value = $COOKIE_3 + "=tracks; ";
                    value += "expires=Sat, 01 Feb 2042 01:20:42 GMT; path=/; domain= " + $DOMAIN + ";";
                    document.cookie = value;
                } else {
                    // album 
                    $('#typeTracks').addClass('off');
                    $('#typeAlbums').removeClass('off');
                    $('.iPhoneCheckHandleRight.activeTracks').addClass('activeAlbums');
                    $('.iPhoneCheckHandleRight.activeTracks').removeClass('activeTracks');

                    // Cookie_3
                    value = $COOKIE_3 + "=albums; ";
                    value += "expires=Sat, 01 Feb 2042 01:20:42 GMT; path=/; domain= " + $DOMAIN + ";";
                    document.cookie = value;
                }
            }, this)

        });
    }

    // Gets the cookie to initially set the platforms
    var cookieValue = document.cookie.split($COOKIE + '=')[1] || "";

    if (cookieValue === null) {
        cookieValue = $default_platforms;
    } else {
        cookieValue = decodeURIComponent(cookieValue.split(';')[0]);
    }

    this.selectedPlatforms = cookieValue;

    var arrayCookieContent = cookieValue.split(',');
    $.each(arrayCookieContent, function(index, pltf) {
        $('#pltf' + pltf).attr('on', 'yes').removeClass("off");

    });

    // Gets the cookie for the 'tracks' vs 'albums' preference
    cookieValue = document.cookie.split($COOKIE_3 + '=')[1] || "";

    if (cookieValue !== null) {
        if (cookieValue.split(";")[0] === 'albums') {
            this.searchTypeCheckBox.click();
        }
    }

};

SearchUI.prototype.bindObjects = function() {

    /******* SEARCH INITIATED *******/
    this.form.submit($.proxy(function(e) {

        e.preventDefault();

        $(".hideAll").fadeOut();
        this.advanced.hide();
        this.optionsButton.removeClass('shd');

        // We get the values
        var queryString = $.trim(this.queryField.val()),
            strictMode = this.strictModeCheckBox.is(':checked');

        // Do we search for tracks or albums ? 0 = track, 1 = album
        var itemType = this.searchTypeCheckBox.is(':checked') ? 0 : 1;

        // Is it a trap ?
        if (queryString.match(/http\:\/\/tuneefy\.com\/[t|a]\/[a-zA-Z0-9]+/)) {
            window.location.href = queryString;
        }

        // Has the user entered something interesting as a query ?
        if (queryString === "" || queryString === $query_label || this.selectedPlatforms === "") {
            return false;
        }

        this.searchButton.attr('disabled', 'disabled');

        console.log('SearchUI >>> search.launched for itemType : ' + itemType);
        $(this.controller).trigger("tuneefy.search.launched", itemType);
        console.log('SearchUI >>> search.start');
        $(this.controller).trigger("tuneefy.search.start", [queryString, itemType, strictMode, this.selectedPlatforms, 100]);

    }, this));


    /******* QUERY INPUT ON FOCUS AND BLUR *******/
    this.queryField.click($.proxy(function(e) {

        if (this.queryField.val() === $query_label) {
            this.queryField.val("");
        }

        $(e.target).select();
        $("#basic").addClass("focused");
        $(".hideAll").fadeTo(500, 0.5);

        // Gets the cookie for the 'never again' preference
        var cookieValue = document.cookie.split($COOKIE_2 + '=')[1];

        if (cookieValue === null) {
            $("#help").fadeIn();
        }

        e.stopPropagation();

    }, this));

    /******* CLICK IN HELP *******/
    this.searchForIt.click($.proxy(function(e) {

        this.queryField.val($(e.target).html());
        this.form.submit();

    }, this));

    /******* RESET BUTTON *******/
    this.queryField.keyup($.proxy(function() {
        if (this.queryField.val() !== $query_label && $.trim(this.queryField.val()).length !== 0) {
            this.resetField.show();
        } else {
            this.resetField.hide();
        }

    }, this));

    this.resetField.click($.proxy(function(e) {
        this.queryField.val("");
        this.queryField.focus();
        this.resetField.hide();
        e.stopPropagation();
    }, this));

    /******* CLOSE HELP *******/
    this.closeHelp.click($.proxy(function(e) {

        $("#help").fadeOut();
        this.queryField.focus();
        e.stopPropagation();

    }, this));

    /******* CLOSE FOREVER HELP *******/
    this.closeForever.click($.proxy(function(e) {

        // Sets the cookie_2
        var value = $COOKIE_2 + "=neverAgain; ";
        value += "expires=Sat, 01 Feb 2042 01:20:42 GMT; path=/; domain= " + $DOMAIN + ";";
        document.cookie = value;
        $("#help").fadeOut();
        this.queryField.focus();
        e.stopPropagation();

    }, this));

    /******* OPTIONS *******/
    this.optionsButton.click($.proxy(function(e) {

        this.advanced.toggle();
        this.optionsButton.toggleClass('shd');
        e.stopPropagation();

    }, this));

    // When we blur() outside the advanced options div, we must close it
    $(".hideAll").click(function() {}); // Trick for iPhone bug http://www.quirksmode.org/blog/archives/2010/09/click_event_del.html
    $('html').click($.proxy(function() {

        // Hiding advanced options
        this.advanced.hide();
        this.optionsButton.removeClass('shd');

        // removing the halo
        $("#basic").removeClass("focused");
        $(".hideAll").fadeOut();

        // Hiding help
        $("#help").fadeOut();

        // Filling with help text
        if (this.queryField.val() === "") {
            this.queryField.val($query_label);
        }

    }, this));

    // In case we click inside the options div, we must not close it
    this.advanced.click(function(e) {
        e.stopPropagation();
    });

    /******* A CHECKBOX (image) IS CLICKED *******/
    this.platforms.click($.proxy(function(e) {

        if ($(e.target).attr("on") === "yes") {
            $(e.target).attr("on", "no");
            $(e.target).addClass("off");
        } else {
            $(e.target).attr("on", "yes");
            $(e.target).removeClass("off");
        }

        // Which platforms did he choose ?
        var tempSelectedPlatforms = [];

        this.platforms.each(function() {
            if ($(this).attr("on") === "yes") {
                tempSelectedPlatforms.push($(this).attr("rel"));
            }
        });
console.log(tempSelectedPlatforms.toString());
        this.selectedPlatforms = tempSelectedPlatforms.toString();

console.log(this.selectedPlatforms);
        // Sets the cookie
        var value = $COOKIE + "=" + encodeURIComponent(this.selectedPlatforms) + "; " + "expires=Sat, 01 Feb 2042 01:20:42 GMT; path=/; domain= " + $DOMAIN + ";";

        document.cookie = value;

    }, this));

    /******* SEARCH TRIGGERED *******/
    $(this.controller).bind("tuneefy.search.trigger", $.proxy(function() {

        console.log('   SearchUI <<< search.trigger');
        this.form.submit();

    }, this));

    /******* SEARCH FINISHED *******/
    $(this.controller).bind("tuneefy.search.finished", $.proxy(function() {

        console.log('   SearchUI <<< search.finished');
        this.searchButton.removeAttr('disabled');

        this.queryField.blur();

    }, this));

    /******* QUERY UPDATED *******/
    $(this.controller).bind("tuneefy.query.updated", $.proxy(function(e, data) {

        console.log('   SearchUI <<< query.updated');
        this.queryField.val(data.replace(/\+/g, " "));

    }, this));

    /******* NEW RESULTS READY FOR A PLATFORM *******/
    $(this.controller).bind("tuneefy.search.newResultsReady", $.proxy(function() {

        console.log('   SearchUI <<< search.newResultsReady');

    }, this));

    /******* NO RESULTS RETURNED FOR A PLATFORM *******/
    $(this.controller).bind("tuneefy.alert.platformReturnedNoResult", $.proxy(function() {

        console.log('   SearchUI <<< alert.platformReturnedNoResult');

    }, this));

    /******* API SEEMS DOWN *******/
    $(this.controller).bind("tuneefy.alert.apiSeemsDown", $.proxy(function() {

        console.log('   SearchUI <<< alert.apiSeemsDown');

    }, this));
};