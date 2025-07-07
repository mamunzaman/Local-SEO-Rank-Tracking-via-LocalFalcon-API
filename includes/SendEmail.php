<?php
class EmailSender {
    private $to;
    private $subject;
    private $message;
    private $headers;

    public function __construct($to, $subject, $message) {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;

        // Set headers
        $this->headers = "MIME-Version: 1.0" . "\r\n";
        $this->headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    }

    public function sendEmail() {
        // Additional headers
        //$this->headers .= "From: Your Name <forward@itconsultingfirma.com>" . "\r\n";

        // Send email
        /*if (mail($this->to, $this->subject, $this->message, $this->headers)) {
            return true;
        } else {
            return false;
        }*/

        $mail = new PHPMailer;


        $mail->From = 'forward@itconsultingfirma.com';
        $mail->FromName = 'Mailer';
        $mail->addAddress('mcpmbstu@gmail.com', 'User');     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }


    }
}