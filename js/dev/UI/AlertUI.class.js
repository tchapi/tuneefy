var platformReturnedNoResult = "platformReturnedNoResult";
var apiSeemsDown = "apiSeemsDown";

function AlertUI(initialController, mode) { 
  
  this.controller = initialController;

  this.mode = mode; // 0 = silent, 1 = verbose
  this.alertsDiv = $("#alerts");

  this.initUIInitialState();
  this.bindObjects();

};

AlertUI.prototype.initUIInitialState = function() {
  
  this.alertsDiv.empty();
  
  $(".closeAlert").live("click",function(){
    $(this).parent().fadeOut();
  });
  
};

AlertUI.prototype.bindObjects = function() {

   /******* CLEANUP *******/
  $(this.controller).bind("tuneefy.search.launched", $.proxy(function(e, itemType){
  
    console.log('   AlertUI <<< search.launched');
    this.alertsDiv.empty();
    
  }, this));
  
  if (this.mode != 0) { // if (verbose)
  
    /******* NO RESULTS RETURNED FOR A PLATFORM *******/
    $(this.controller).bind("tuneefy.alert.platformReturnedNoResult", $.proxy(function(e, data){
    
      console.log('   AlertUI <<< alert.platformReturnedNoResult');
      this.notify(_search_alert, _p[data], platformReturnedNoResult);
      
    }, this));
    
    /******* API SEEMS DOWN *******/
    $(this.controller).bind("tuneefy.alert.apiSeemsDown", $.proxy(function(e, data){
    
      console.log('   AlertUI <<< alert.apiSeemsDown');
      this.notify(_api_alert, _p[data], apiSeemsDown);
      
    }, this));
    
    /******* INVALID QUERY *******/
    $(this.controller).bind("tuneefy.alert.invalidQuery", $.proxy(function(e){
    
      console.log('   AlertUI <<< alert.invalidQuery');
      this.notify(_invalid_query);
      
    }, this));
  
  }
  
  /******* NO RESULTS FOUND *******/
  $(this.controller).bind("tuneefy.alert.noResultsFound", $.proxy(function(e){
  
    console.log('   AlertUI <<< alert.noResultsFound');
    this.notify(_no_result);
    
  }, this));
};

AlertUI.prototype.notify = function(message, platform, type) {

  var existant = $("span."+type+" span.platform");
  
  if (type == null || existant.length == 0 ) {
    // Message to display one time only, or no pre-existant message
    this.alertsDiv.append(this.format(message.replace('#p#','<span class="color platform">' + platform +'</span>'), type));
    this.alertsDiv.children().last().fadeIn();
  } else {
    // One message already printed
    existant.html(existant.html() + ", " + platform);
  }
  
};

AlertUI.prototype.format = function(message, type) {
  
  return "<span class='alert " + (type?type:"") + "' ><div class=\"triangle\"></div>" + message + "<span class='closeAlert'></span></span>";
  
};
