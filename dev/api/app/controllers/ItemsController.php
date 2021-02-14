<?php

/**
 * Controlador para la gestión de Items.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class ItemsController extends SessionController {

  /**
   * Devuelve el listado de items.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_items = self::$USER->countOwn('item');
    $items = R::getAll("SELECT id, name FROM item WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_items' => $total_items,
      'items' => $items,
    ]);
  }

  /**
   * Devuelve los valores de un item.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $item = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownItemList;
    if($item) {
      $item = reset($item);
      $total_items = $item ? 1:0;
      self::json([
        'total_items' => $total_items,
        'item' => [
          'id' => $item->id,
          'name' => $item->name,
          'user_id' => $item->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_items' => 0,
        'item' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un item.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function post($params=[]) {
    self::check_api_token();
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $id = intval($params['id']);
    if($id>0) {
      $mode = 'update';
      $item = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownItemList;
      $item = reset($item);
    } else {
      $mode = 'create';
      $item = R::dispense('item');
      $item->user_id = self::$USER->id;
    }
    $total_items = $item->id>0 ? 1 : 0;
    if($item) {
      $item->import( $data, 'name' );
      R::store( $item );
      self::json([
        'total_items' => $total_items,
        'mode' => $mode,
        'item' => [
          'id' => $item->id,
          'name' => $item->name,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_items' => 0,
        'item' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}