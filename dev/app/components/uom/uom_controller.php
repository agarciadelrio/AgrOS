<?php

/**
 * [Description UomController]
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