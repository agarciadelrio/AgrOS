<?php

/**
 * Controlador para métodos comunes del API.
 *
 * Métodos varios para acceso a datos.
 */
class ApiController extends Controller {
  /**
   * @var [OPTIONS_TABLES]
   */
  static $OPTIONS_TABLES = [
    'category' => [],
    'company' => [],
    'contact' => [],
    'farm' => [],
    'member' => [],
    'parcel' => [],
    'plot' => [],
    'product' => [],
    'task' => [],
    'team' => [],
    'uom' => [],
  ];
  /**
   * Obtienes el listado id,name de un modelo
   *
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function options($params=[]) {
    $user = Sessions::authenticate();
    $table = $params['collection'];
    if(in_array($table, array_keys(static::$OPTIONS_TABLES))) {
      $items = R::getAll( "SELECT id, name FROM $table ORDER BY name, id" );
    } else {
      $items = [];
    }
    self::json([
      'msg' => 'Api Options',
      'items' => array_values($items),
    ]);
  }
}