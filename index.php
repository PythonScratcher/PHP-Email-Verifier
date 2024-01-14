<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/PHPMailer.php'; 
require 'src/SMTP.php';
require 'src/Exception.php';


if (isset($_GET['email'])) {
    $email = $_GET['email'];

   
    $verificationCode = generateVerificationCode();

   
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'dark@youremail.co.uk'; // Replace with your SMTP username
        $mail->Password = 'passwordhere'; // Replace with your SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('your email', 'Verification'); // Replace with your email and name
        $mail->addAddress($email);

        
        $mail->isHTML(true);
        $mail->Subject = 'Verification Code';
        $mail->Body = 'Your verification code is: ' . $verificationCode . '<br><br><br>Service by <a href="https://github.com/PythonScratcher/PHP-Email-Verifier">PythonScratcher</a>';

        // Send the email
        $mail->send();

        // Check if json parameter is provided
        if (isset($_GET['json']) && ($_GET['json'] === 'true' || $_GET['json'] === 'yes')) {
            // Return JSON response
            echo json_encode(['code' => $verificationCode]);
        } else {
            // Return raw response
            echo $verificationCode;
        }
    } catch (Exception $e) {
 
        echo 'Error: ' . $e->getMessage();
    }
} else {

    echo 'Error: Email parameter is missing.';
}


function generateVerificationCode($length = 6)
{
    return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $length));
}

?>
