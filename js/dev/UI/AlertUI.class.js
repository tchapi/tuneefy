/*global $,console,$p,$api_alert,$search_alert,$no_result,$invalid_query*/
/*jslint browser: true*/
var platformReturnedNoResult = "platformReturnedNoResult";
var apiSeemsDown = "apiSeemsDown";

var AlertUI = function(initialController, mode) {

    this.controller = initialController;

    this.mode = mode; // 0 = silent, 1 = verbose
    this.alertsDiv = $("#alerts");

    this.initUIInitialState();
    this.bindObjects();

};

AlertUI.prototype.initUIInitialState = function() {

    this.alertsDiv.empty();

    $(".closeAlert").live("click", function() {
        $(this).parent().fadeOut();
    });

};

AlertUI.prototype.bindObjects = function() {

    /******* CLEANUP *******/
    $(this.controller).bind("tuneefy.search.launched", $.proxy(function() {

        console.log('   AlertUI <<< search.launched');
        this.alertsDiv.empty();

    }, this));

    if (this.mode !== 0) { // if (verbose)

        /******* NO RESULTS RETURNED FOR A PLATFORM *******/
        $(this.controller).bind("tuneefy.alert.platformReturnedNoResult", $.proxy(function(e, data) {

            console.log('   AlertUI <<< alert.platformReturnedNoResult');
            this.notify($search_alert, $p[data], platformReturnedNoResult);

        }, this));

        /******* API SEEMS DOWN *******/
        $(this.controller).bind("tuneefy.alert.apiSeemsDown", $.proxy(function(e, data) {

            console.log('   AlertUI <<< alert.apiSeemsDown');
            this.notify($api_alert, $p[data], apiSeemsDown);

        }, this));

        /******* INVALID QUERY *******/
        $(this.controller).bind("tuneefy.alert.invalidQuery", $.proxy(function() {

            console.log('   AlertUI <<< alert.invalidQuery');
            this.notify($invalid_query);

        }, this));

    }

    /******* NO RESULTS FOUND *******/
    $(this.controller).bind("tuneefy.alert.noResultsFound", $.proxy(function() {

        console.log('   AlertUI <<< alert.noResultsFound');
        this.notify($no_result);

    }, this));
};

AlertUI.prototype.notify = function(message, platform, type) {

    var existant = $("span." + type + " span.platform");

    if (type === null || existant.length === 0) {
        // Message to display one time only, or no pre-existant message
        this.alertsDiv.append(this.format(message.replace('#p#', '<span class="color platform">' + platform + '</span>'), type));
        this.alertsDiv.children().last().fadeIn();
    } else {
        // One message already printed
        existant.html(existant.html() + ", " + platform);
    }

};

AlertUI.prototype.format = function(message, type) {

    return "<span class='alert " + (type || "") + "' ><div class=\"triangle\"></div>" + message + "<span class='closeAlert'></span></span>";

};