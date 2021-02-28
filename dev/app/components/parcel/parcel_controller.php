<?php
/**
 * Controlador para Parcelas.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class ParcelController extends Controller {
  static $TABLE_NAME = 'parcel';
  static $TABLE_COLUMNS = ['name'];
  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    $user = Sessions::authenticate();
    self::render('parcel/_index', $params);
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
    $params['parcel'] = R::load('parcel', $params['id']);
    self::render('parcel/_get', $params);
  }
}