<?php

/**
 * Controlador para hacer pruebas de desarrollo
 *
 * @package DEV
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class TestController extends Controller {
  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
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

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function _pdf($params=[]) {

    static::pdf('pdf/hello');
    echo "HOLA";
    exit;
  }
}