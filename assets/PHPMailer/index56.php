<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

$mail = new PHPMailer(true);                                                    // Passing `true` enables exceptions
try {
    //Server settings
    //$mail->SMTPDebug = 2;                                                     // Enable verbose debug output
    $mail->isSMTP();                                                            // Set mailer to use SMTP
    $mail->Host = 'mail.bijuteriacontafina.com';                                // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                                                     // Enable SMTP authentication
    $mail->Username = 'geral@bijuteriacontafina.com';                           // SMTP username
    $mail->Password = 'Angola2018';                                             // SMTP password
    $mail->SMTPSecure = 'tls';                                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                                          // TCP port to connect to

    //Recipients
    $mail->setFrom('geral@bijuteriacontafina.com', 'Mailer');
    $mail->addAddress('rosariomassango@hotmail.com', 'Joe User');               // Add a recipient              // Name is optional
    //$mail->addReplyTo('geral@bijuteriacontafina.com', 'Information');

    //Content
    $mail->isHTML(true);                                                // S    et email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if($mail->send()){
        echo 'Enviado';
    }else{
        echo 'Nao Enviado';
    }
    
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}