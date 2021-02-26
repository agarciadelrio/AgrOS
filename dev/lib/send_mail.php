<?php


#include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/vendor/securimage/securimage.php';
#
#$securimage = new Securimage();
#if ($securimage->check($_POST['captcha_code']) == false) {
#  // Código de seguridad incorrecto
#  echo "<html><body><p>El código de seguridad introducido es incorrecto</p>";
#  echo "<p>Por favor vuelva <a href='javascript:history.go(-1)'>atrás</a> e inténtelo de nuevo.</p></body></html>";
#  exit;
#}
#$subject = 'Formulario web Extintores Guanche: ' . $_POST['subject'];
#
#$body = "<html><body style='font-size:20px;'><dl>";
#foreach($_POST as $k => $v) {
#  $body .= "<dt style='color:blue;'>$k:</dt><dd style='font-weight:bold;'>$v</dd>";
#}
#$body .= "</dl></body></html>";
#$to = "agarciadelrio@gmail.com";
#$headers = 'From: extintoresguanche@extintoresguanche.com' . "\r\n" .
#   'Reply-To: extintoresguanche@extintoresguanche.com' . "\r\n" .
#   'X-Mailer: PHP/' . phpversion();
#$headers .= "MIME-Version: 1.0\r\n";
#$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
#mail($to, $subject, $body, $headers);
#$to = "extintoresguanche@extintoresguanche.com";
#if (mail($to, $subject, $body, $headers)) {
#  header('Location: /msg/ok');
#  exit();
#} else {
#  header('Location: /msg/fail');
#  exit();
#}