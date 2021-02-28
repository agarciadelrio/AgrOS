<?php

/**
 * Unidades de medida.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class UomController extends Controller {
  static $TABLE_NAME = 'uom';
  static $TABLE_COLUMNS = ['abbr','factor','name','symbol'];
  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    $user = Sessions::authenticate();
    self::render('uom/_index', $params);
  }

}