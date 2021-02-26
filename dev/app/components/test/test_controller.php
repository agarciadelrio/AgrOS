<?php

class TestController extends Controller {
  public static function _mail($params=[]) {

    $to = 'agarciadelrio@gmail.com';
    //$subject = 'Pruebas de email';
    //$params['url'] = SITE_URL . '/active-account/343433343040342i3u4iuifwuif';
    //$body = self::partial('mail','mails/register-ok', $params);
    //$to = $user->email;
    $subject = subjectUtf8('[AgrOS]: recuperación de acceso');
    $params['url'] = SITE_URL . '/reset-password/' . '343433343040342i3u4iuifwuif';
    $body = self::partial('mail','mails/reset-password', $params);
    //$body = 'KOSA';
    $mail_response = self::mail($to, $subject, $body);
    if(!$mail_response) {
      throw new Exception('No hemos podido enviar el correo de registro a ' . $user->email);
    }
    if($mail_response) {
      self::json([
        'msg' => 'mail',
        'out' => 'OK',
        'body' => $body,
      ]);
    } else {
      self::json([
        'msg' => 'error al enviar email',
      ]);
    }
  }
}