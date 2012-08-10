<?php

  require('../config.php');
    
  require(_PATH.'include/database/mysql.php');
  require(_PATH.'include/database/mysql_admin_functions.php');

  $allMails = getEmails();
  
  function out($id, $mail){
      $ret = "<li><span class=\"color\">" . $id . "</span> - <span class=\"desc\">" . $mail . "</span></li>";
      return $ret;
  }

  while (list ($key, $val) = each ($allMails) ) {
    echo out($key, $val);
  }
