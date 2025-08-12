<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Composer autoload (expect vendor directory in project root)
require_once __DIR__ . '/vendor/autoload.php';
$config = require __DIR__ . '/config.php';

function send_email($to, $subject, $body) {
  global $config;

  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = $config['smtp_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp_user'];
    $mail->Password = $config['smtp_pass'];
    $mail->SMTPSecure = 'ssl';
    $mail->Port = $config['smtp_port'];

    $mail->setFrom($config['smtp_user'], 'SkuzE');
    $mail->addAddress($to);

    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();
  } catch (Exception $e) {
    error_log("Mailer Error: " . $mail->ErrorInfo);
  }
}
