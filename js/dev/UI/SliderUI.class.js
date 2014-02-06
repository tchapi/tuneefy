/*global $,console*/
/*jslint browser: true*/
function SliderUI(interval) {

    this.interval = interval;
    this.picks = $('.pick');
    this.pagers = $(".pickPagerItem");

    this.initUIInitialState();

};

SliderUI.prototype.goNext = function() {

    var active = this.picks.filter(':visible');
    var next = active.next();

    if (next.length == 0) {
        next = this.picks.first();
    }

    next.css("left", "400px");
    next.show();
    next.animate({
        "left": "30px"
    }, "slow");

    this.pagers.removeClass("active");

    /* IE does not suport nth-child ... */
    var nextPager = document.getElementById("pickPager").childNodes[this.picks.index(next) * 2 + 1];

    if (nextPager == null || nextPager.length == 0) {
        this.pagers.first().addClass("active");
    } else  {
        nextPager.className += " active";
    }

    active.animate({
        "left": "-300px"
    }, "slow");
    active.fadeOut("slow");

};

SliderUI.prototype.goPrev = function() {

    var active = this.picks.filter(':visible');
    var prev = active.prev();

    if (prev.length == 0) {
        prev = this.picks.last();
    }

    prev.css("left", "-300px");
    prev.show();
    prev.animate({
        "left": "30px"
    }, "slow");

    /* IE does not suport nth-child ... */
    var prevPager = document.getElementById("pickPager").childNodes[this.picks.index(prev) * 2 + 1];

    if (prevPager == null || prevPager.length == 0) {
        this.pagers.last().addClass("active");
    } else  {
        prevPager.className += " active";
    }

    active.animate({
        "left": "400px"
    }, "slow");
    active.fadeOut("slow");

};

SliderUI.prototype.initUIInitialState = function() {

    /* TRICK */
    var myself = this;

    function callMethod() {
        myself.goNext();
    }

    setInterval(callMethod, this.interval);

};