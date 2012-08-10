<?php

  require('../config.php');
    
  require(_PATH.'include/database/DBUtils.class.php');
  require(_PATH.'include/database/DBStats.class.php');
  require(_PATH.'include/database/DBConnection.class.php');

  if (isset($_REQUEST['id'])) {
  
    $id = intval($_REQUEST['id']);
  
    $success = DBStats::addAPick($id);
    
    if ($success){
      echo "Ok.";
    } else echo "Failed.";
    
    
  } else {
  
    echo "No way.";
  
  }
  