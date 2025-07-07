<?php
/************************
 * All E-Mail setting Files and all added in AjaxProcess.php
 * ************************************************************
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


$mail = new PHPMailer(true);

$mail->CharSet = "UTF-8";
$mail->SMTPDebug = false;
$mail->isSMTP();                                            //Send using SMTP
$mail->Host       = 'S30.internetwerk.de';                     //Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
$mail->Username   = 'result@boovis.com';                     //SMTP username
$mail->Password   = 'dohyabYegArgic5';                               //SMTP password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
$mail->Port       = 465;

$mailSenderName = "boovis - BOOSTING YOUR VISIBILITY";
$mail->setFrom('result@boovis.com', $mailSenderName);
$mail->addAddress($mm_email, $mm_nachname);     // Add a recipient