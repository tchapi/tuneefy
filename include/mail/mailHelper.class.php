<?php

date_default_timezone_set('Europe/Paris');

require_once('class.phpmailer.php');

class MailerHelper
{

  public static function sendMail($email, $message)
  {

    $mail = new PHPMailer();
    
    $mail->CharSet = 'UTF-8';

    $mail->IsSMTP();                                      // set mailer to use SMTP
    
    $mail->From = _CONTACT_MAIL;
    $mail->AddAddress(_TEAM_MAIL);

    $mail->IsHTML(true);                                  // set email format to HTML

    $mail->Subject = "[CONTACT] $email (via " . _SITE_URL .")";
    $mail->Body    = $email.' sent a message from the site : <br /><br />'.nl2br($message);

    try
    {
      $mail->Send();
      return true;
    }
    catch(Exception $e)
    {
      // log error
       $mail->ErrorInfo;
       return false;
    }
  }

  public static function sendWatchdogMail($message)
  {

    $mail = new PHPMailer();
    
    $mail->CharSet = 'UTF-8';

    $mail->IsSMTP();                                      // set mailer to use SMTP
    
    $mail->From = _TEAM_MAIL;
    $mail->AddAddress(_WATCHDOG_MAIL);

    $mail->IsHTML(true);                                  // set email format to HTML

    $mail->Subject = "tuneefy watchdog report";
    $mail->Body    = nl2br($message);

    try
    {
      $mail->Send();
      return true;
    }
    catch(Exception $e)
    {
      // log error
       $mail->ErrorInfo;
       return false;
    }
  }
}
