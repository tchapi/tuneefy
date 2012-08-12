function PlaylistsUI(initialController){

  this.controller = initialController;
  
  this.loginForm = $("#playlist-login");
  this.form = $("#find");
  this.queryField = $("#query");
  this.searchButton = $("#launch");
  /*
  this.processButton = $("#process");
  
  this.selectedPlatforms = ""; // By default
  */
  this.platformButtons = $("#loginButtons a");
  this.loginFields = $("#loginFields");
  this.resetField = $("#resetQuery");
  
  this.initUIInitialState();
  this.bindObjects();

  this.currentAccessToken = null;
  
};

PlaylistsUI.prototype.initUIInitialState = function() {

  // Re-enable the button in case and cleanse platforms checkBoxes
  this.searchButton.removeAttr('disabled');
	
};

PlaylistsUI.prototype.bindObjects = function() {
  
  /******* PLATFORM CHOICE BUTTON *******/
  this.platformButtons.click($.proxy(function(event){
  
    this.platformButtons.hide();
    this.loginFields.show();
    
    $("#platform").val($(event.target).attr("rel"));
    
    event.stopPropagation();
        
  }, this));

  /******* BACK TO PLATFORM CHOICE BUTTON *******/
  $("#back").click($.proxy(function(e){
  
    this.loginFields.hide();
    this.platformButtons.fadeIn();
    
    e.preventDefault();
        
  }, this));
  

  /******* LOGIN *******/
  $("#log").click($.proxy(function(e){
  
    console.log('PlaylistsUI >>> login.playlist');

    if ($("#platform").val() == 0) { // DEEZER

      DZ.login($.proxy(function(response) {
        if (response.authResponse) {
          DZ.api('/user/me', $.proxy(function(response) {
            
            DZ.getLoginStatus($.proxy(function(response) {
              if (response.authResponse) {

                this.currentAccessToken = response.authResponse.accessToken;

                console.log('INLINE >>> login.succeeded');
                console.log('INLINE >>> '+this.currentAccessToken);
                $(this.controller).trigger("tuneefy.login.succeeded");
              }
            }, this));

          }, this));
        } else {
          console.log('Tuneefy >>> login.failed');
          $(this.controller).trigger("tuneefy.login.failed");
        }
      }, this), {perms: 'manage_library, offline_access'});

    } else { // Platforms with no JS SDK, eg QOBUZ

      $.getJSON("/include/platforms/signIn.php", {id: $("#platform").val(), username: $("#username").val(), password: hex_md5($("#passwd").val())},$.proxy(function(data, status){
           
        if (data.data && data.data.access_token != null) {

          this.currentAccessToken = data.data.access_token;

          console.log('INLINE >>> login.succeeded');
          console.log('INLINE >>> '+this.currentAccessToken);
          $(this.controller).trigger("tuneefy.login.succeeded");

        } else {

          console.log('Tuneefy >>> login.failed');
          $(this.controller).trigger("tuneefy.login.failed");

        }

      }, this));

    }

    e.preventDefault();
        
  }, this));
  
  /******* LOGOUT *******/
  $("#logout").click($.proxy(function(e){
  
    this.loginFields.hide();
    this.loginForm.find("input[type=text], input[type=password]").css('background', 'white');
    $("#logged").hide();
    this.form.hide();
    this.platformButtons.fadeIn();
    this.currentAccessToken = false;

    e.preventDefault();
        
  }, this));

  /******* LOGIN SUCCEEDED *******/
  $(this.controller).bind("tuneefy.login.succeeded", $.proxy(function(){
  
    console.log('   PlaylistsUI <<< login.succeeded');

    this.form.fadeIn();
    this.loginFields.hide();
    this.loginForm.find("input[type=text], input[type=password]").css('background', 'white');
    $("#logged").show();
    
  }, this));
  
  /******* LOGIN FAILED *******/
  $(this.controller).bind("tuneefy.login.failed", $.proxy(function(){
  
    console.log('   PlaylistsUI <<< login.failed');

    this.form.hide();
    this.loginFields.show();
    this.loginForm.find("input[type=text], input[type=password]").css('background', 'salmon');
    $("#logged").hide();
    
  }, this));
          
  /******* SEARCH INITIATED *******/
  this.form.submit($.proxy(function(e){
  
    e.preventDefault();
    
    $(".hideAll").fadeOut();
 	  
    // We get the values
    var queryString = $.trim(this.queryField.val());
       
    // Has the user entered something interesting as a query ?
    if (queryString == "" || queryString == _playlists_query_label) return false;
   
    this.searchButton.attr('disabled','disabled');
    
    console.log('PlaylistsUI >>> retrieve.playlist');
    $(this.controller).trigger("tuneefy.retrieve.playlist", [$("#platform").val(), queryString]);
    
  }, this));
  
  
  /******* QUERY INPUT ON FOCUS AND BLUR *******/
  this.queryField.click($.proxy(function(e){
  
    if (this.queryField.val() == _playlists_query_label) {
      this.queryField.val("");
    }
    
    $(e.target).select();
    $("#basic").addClass("focused");
    $(".hideAll").fadeTo(500, 0.5);
    
    e.stopPropagation();
        
  }, this));

  // When we blur() outside the advanced options div, we must close it
  $(".hideAll").click(function(){}); // Trick for iPhone bug http://www.quirksmode.org/blog/archives/2010/09/click_event_del.html
  $('html').click($.proxy(function() {
    
    // removing the halo
    $("#basic").removeClass("focused");
    $(".hideAll").fadeOut();
    
    // Filling with help text
    if (this.queryField.val() == "") {
      this.queryField.val(_playlists_query_label);
    }
    
  }, this));
  
  /******* RESET BUTTON *******/
  this.queryField.keyup($.proxy(function(e) {
    if (this.queryField.val() != _playlists_query_label && $.trim(this.queryField.val()).length != 0)
      this.resetField.show();
    else
      this.resetField.hide();
  
  }, this));
  
  this.resetField.click($.proxy(function(e){
    this.queryField.val("");
    this.queryField.focus();
    this.resetField.hide();
    e.stopPropagation();
  }, this));

  
  /******* RETRIEVE FINISHED *******/
  $(this.controller).bind("tuneefy.retrieve.finished", $.proxy(function(){
  
    console.log('   PlaylistsUI <<< retrieve.finished');

    this.queryField.blur();
    
  }, this));

};