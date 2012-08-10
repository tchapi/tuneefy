/* 
 * Constructor Function
 */
function Links() {

  this.links = [];

};

/* 
 * Add a Single link Function
 */
Links.prototype.addLink = function(type, link){

  this.links[type] = link;
  
};


/* 
 * Merge two items Function
 */
Links.prototype.merge = function(links){

  this.links = $.extend(links.links, this.links);
  
};

/* 
 * Get the span of the links Function
 */
Links.prototype.getSpan = function(){

  var size = 0;
  for (type in this.links) {
      if (this.links.hasOwnProperty(type)) size++;
  }
  return size;

};

/* 
 * Get one link of a type Function
 */
Links.prototype.findOne = function(type){
  
  return this.links[type];

};