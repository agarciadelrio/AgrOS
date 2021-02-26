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
    $params['title']='Home';
    self::render('home/_index', $params);
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function about($params) {
    self::$layout = 'minimal';
    View::bodyClassTheme('body-about');
    self::render('home/_about');
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function legal($params) {
    self::$layout = 'minimal';
    View::bodyClassTheme('body-about');
    self::render('home/_legal');
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function login($params) {
    self::$layout = 'minimal';
    View::bodyWrapperClass('home-form');
    self::render('home/_login');
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function logout($params) {
    self::$layout = 'minimal';
    View::bodyClassTheme('body-about');
    Sessions::destroy();
    self::render('home/_logout');
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function register($params=[]) {
    self::$layout = 'minimal';
    View::bodyWrapperClass('home-form');
    $params['email'] = NULL;
    self::render('home/_register', $params);
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function recover($params) {
    self::$layout = 'minimal';
    View::bodyWrapperClass('home-form');
    self::render('home/_recover');
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function login_error($params) {
    self::render('home/_login-error');
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function session_expired($params) {
    self::$layout = 'minimal';
    View::bodyWrapperClass('home-form');
    self::render('home/_session-expired');
  }
}
?>