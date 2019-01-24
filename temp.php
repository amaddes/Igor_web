<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$better_token = md5(uniqid(rand(),1));
$regLink = '<a href="https://butlerigor.ru/registration.php?reg='.$better_token.'">Подтвердить регистрацию</a>';
$file = 'reg-email.html';
$FileSourse = file_get_contents($file);
$FileSourse = str_replace('<!--Add regLink-->',$regLink,$FileSourse);
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

function smtpmailer($to, $from, $from_name, $subject, $body) { 
    global $error;
    $mail = new PHPMailer();  // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 4;  // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true;  // authentication enabled
    $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
    $mail->SMTPOptions = array(
        'ssl' => [
            'verify_peer' => true,
            'verify_depth' => 3,
            'allow_self_signed' => true,
            'peer_name' => 'mail.1c-bas.ru',
            'cafile' => '/usr/share/ca-certificates/extra/mail.1c-bas.ru.crt',
        ],
    );
    $mail->Host = 'mail.1c-bas.ru';
    $mail->Port = 587; 
    $mail->Username = "admin@butlerigor.ru";  
    $mail->Password = "Parol28071982!";           
    $mail->SetFrom($from, $from_name);
    $mail->Subject = $subject;
    $mail->msgHTML($body);
    $mail->AddAddress($to);
    if(!$mail->Send()) {
    $error = 'Mail error: '.$mail->ErrorInfo; 
    return false;
    } else {
    $error = 'Message sent!';
    return true;
    }
   }
$sending = smtpmailer("amadeus@tplast.org", "admin@butlerigor.ru", "admin", "test", $FileSourse);
var_dump($sending);
?>

