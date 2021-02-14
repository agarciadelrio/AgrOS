<?php
/**
 * Controlador para hacer pruebas.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class TestController extends Controller {

  public static function test_view($params=[]) {
    switch ($params['name']) {
      case 'registers':
        $to = 'agarciadelrio@gmail.com';
        $subject = 'Pruebas de email';
        $params['url'] = SITE_URL . '/active-account/343433343040342i3u4iuifwuif';
        $body = self::partial('mail','mails/register-ok', $params);
        if(self::mail($to, $subject, $body)) {
          self::render('users/register', $params);
        } else {
          self::json([
            'msg' => 'error al enviar email',
          ]);
        }
        break;
      default:
        self::render($params['name'],[]);
        #self::json([
        #  'msg' => 'test_view',
        #  'params' => $params,
        #]);
    }
  }
}
