<?php
/**
 * Controlador para gestionar el Sitio Público.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class HomeController extends Controller {
  static $layout='web';

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    self::$layout = 'minimal';
    $params['version']='0.0.0.1a';
    self::render('home/index', $params);
  }


  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function about($params) {
    View::bodyClassTheme('body-about');
    self::render('home/about');
  }


  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function login($params) {
    View::bodyWrapperClass('home-form');
    self::render('home/login');
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function register($params=[]) {
    View::bodyWrapperClass('home-form');
    $params['email'] = NULL;
    self::render('home/register', $params);
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function legal($params) {
    self::render('home/legal');
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function remember_password($params) {
    self::render('home/remember-password');
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function login_error($params) {
    self::render('home/login-error');
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function session_expired($params) {
    self::render('home/session-expired');
  }
}
?>