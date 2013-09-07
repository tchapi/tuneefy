<?php

require_once('../mail/mailHelper.class.php');

// We verify the referer
if (isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], _SITE_URL) !== false) {

  if (isset($_POST['mail']) && $_POST['mail'] != "" && isset($_POST['message']) && $_POST['message'] != "" ) {
  
    $success = MailerHelper::sendMail($_POST['mail'], $_POST['message']);
    
    if ($success) {
      echo "1";
      return;
    }
  }

}

echo "0";
