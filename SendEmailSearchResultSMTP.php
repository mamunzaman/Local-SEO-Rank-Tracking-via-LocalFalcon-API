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
$mail->addAddress($allArrayOfFormData['mm_email'], $allArrayOfFormData['mm_nachname']);     // Add a recipient

//Content
$mail->isHTML(true);

$mail->Subject = $opt_in_subject;
$mail->Body    = $opt_in_email_text . '<br/><br/>';
$mail->Body    .= $print_Email_Location.'<br/><br/>';

if(!$mail->send()) {

    $sql = "DELETE * FROM $database_gateway->tableName WHERE email= :email";

    $data = [
        'email'     => $allArrayOfFormData['mm_email'],
    ];

    $response = $database_gateway->query($sql, $data);
    $count = count((array)$response);

    if($count >=1){
        $jsonDataReturn = array(
            "message"   => $error_not_mail_txt . $mail->ErrorInfo,
            "status"    => false
        );
        echo json_encode($jsonDataReturn);
    }

} else {
    $jsonDataReturn = array(
        "message"   =>  "<span class='successfull-icon-common'></span>$success_email_message",
        "status"    => true
    );
    echo json_encode($jsonDataReturn);

}