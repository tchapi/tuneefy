javascript:(

  function(){
  
    tuneefy_bkmrklt = document.createElement('SCRIPT');
    tuneefy_bkmrklt.type = 'text/javascript';
    tuneefy_bkmrklt.src = '%s%/widget/share.js.php?x=' + (Math.random());
    
    document.getElementsByTagName('head')[0].appendChild(tuneefy_bkmrklt);
    
  })();