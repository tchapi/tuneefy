<?php

  require('../../config.php');
  
  require(_PATH.'include/database/DBUtils.class.php');
  require(_PATH.'include/database/DBLogger.class.php');
  require(_PATH.'include/database/DBConnection.class.php');
  
  require(_PATH.'include/langs/i18nHelper.php');
  require(_PATH.'include/api/request.php');
  
  if ( $action == 'api_doc' ) {

    require(_PATH.'include/request.php');
    $action = 'api_doc'; // it gets overwritten otherwise
    require(_PATH.'templates/header.php');
    require(_PATH.'templates/renderAPIDocumentation.php');
    require(_PATH.'templates/footer.php');
    
  } else if ( $action == 'api' ) {
  
    require(_PATH.'include/api/RestUtils.class.php');
    require(_PATH.'include/api/XMLHelper.class.php');
    
    $data = RestUtils::processRequest();
    
    if ($data->isAuthorised()){
    
      API::processAPICall($calledMethod, $data);
      
    } else {
    
      RestUtils::sendResponse(401);
      
    }
    
  }  else {
 
    require(_PATH.'templates/header_light.php');
    require(_PATH.'templates/404.php');
  }
