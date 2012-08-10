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
    
    $mail->From = 'contact@tuneefy.com';
    $mail->AddAddress('team@tuneefy.com');

    $mail->IsHTML(true);                                  // set email format to HTML

    $mail->Subject = "[CONTACT] $email (via tuneefy.com)";
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
}
