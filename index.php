<?php

error_reporting(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

require "email_config.php";

$headers = getallheaders();
$http_origin = $_SERVER['HTTP_ORIGIN'];

if (in_array($http_origin, $allowed_host)) {

  if (isset($_REQUEST["recpt_email"]) && isset($_REQUEST["subject"]) && isset($_REQUEST["message"])) {

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Mailer = "smtp";
    $mail->SMTPDebug  = 0;
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;
    $mail->Host       = $smtp_host;
    $mail->Username   = $your_gmail;
    $mail->Password   = $app_password; //your app password
    $mail->IsHTML(true);
    $mail->AddAddress($_REQUEST["recpt_email"]);
    $mail->SetFrom($your_gmail, $your_name);
    $mail->Subject = $_REQUEST["subject"];
    $content = "<b>" . $_REQUEST["message"] . "</b>";

    $mail->MsgHTML($content);

    if (!$mail->Send()) {

      $status = ["status" => "failed"];

      header("Access-Control-Allow-Origin: *");
      header('Access-Control-Allow-Methods: GET , POST');
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($status, true);
    } else {

      $status = ["status" => "success"];

      header("Access-Control-Allow-Origin: *");
      header('Access-Control-Allow-Methods: GET , POST');
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($status, true);
    }
  } 
  else {

    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
    exit;
  }
} 
else {

  header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
  exit;
}

?>
