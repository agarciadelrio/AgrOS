<?php
/**
 * Controlador para el panel de control del usuario.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class PlotController extends Controller {
  static $TABLE_NAME = 'plot';
  static $TABLE_COLUMNS = ['name'];

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    $user = Sessions::authenticate();
    $params['ses'] = json_encode($_SESSION);
    $params['user'] = $user;
    self::render('plot/_index', $params);
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function get($params=[]) {
    $user = Sessions::authenticate();
    $params['ses'] = json_encode($_SESSION);
    $params['user'] = $user;
    $params['plot'] = R::load('plot', $params['id']);
    self::render('plot/_get', $params);
  }
}