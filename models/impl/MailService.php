<?php

require 'models/ext/class.phpmailer.php';

class MailService {

    public static function SendError($subject, $message) {
        try {
            $mail = new PHPMailer;

            $mail->IsSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'mail.genesisville.com';  // Specify main and backup server
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'sales@genesisville.com';                            // SMTP username
            $mail->Password = 'admin@1admin@1';

            $mail->From = 'error@genesisville.net';
            $mail->FromName = 'GenesisVille.net';  // Add a recipient
            $mail->AddAddress('norman_osaruyi@yahoo.com');               // Name is optional

            $mail->IsHTML(false);                                  // Set email format to HTML

            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->Send();
        } catch (Exception $e) {
            
        }
    }

    public static function SendMail($subject, $message, $email) {
        try {
            $mail = new PHPMailer;

            $mail->IsSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'mail.genesisville.com';  // Specify main and backup server
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'sales@genesisville.com';                            // SMTP username
            $mail->Password = 'admin@1admin@1';

            $mail->From = 'sales@genesisville.net';
            $mail->FromName = 'GenesisVille.net';  // Add a recipient
            $mail->AddAddress($email);               // Name is optional

            $mail->IsHTML(false);                                  // Set email format to HTML

            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->Send();
        } catch (Exception $e) {
            
        }
    }
}

?>
