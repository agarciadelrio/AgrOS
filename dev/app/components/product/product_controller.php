<?php

/**
 * Productos
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class ProductController extends Controller {
  static $LIMIT=10;
  static $TABLE_NAME = 'product';
  static $TABLE_COLUMNS = ['id','name','price','category_id','uom_id','code','description'];

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    $user = Sessions::authenticate();
    $params['label_col'] = 3;
    self::render('product/_index', $params);
  }

}