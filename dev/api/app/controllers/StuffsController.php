<?php

/**
 * Controlador para la gestión de Stuffs.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class StuffsController extends SessionController {

  /**
   * Devuelve el listado de stuffs.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_stuffs = self::$USER->countOwn('stuff');
    $stuffs = R::getAll("SELECT id, name FROM stuff WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_stuffs' => $total_stuffs,
      'stuffs' => $stuffs,
    ]);
  }

  /**
   * Devuelve los valores de un stuff.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $stuff = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownStuffList;
    if($stuff) {
      $stuff = reset($stuff);
      $total_stuffs = $stuff ? 1:0;
      self::json([
        'total_stuffs' => $total_stuffs,
        'stuff' => [
          'id' => $stuff->id,
          'name' => $stuff->name,
          'user_id' => $stuff->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_stuffs' => 0,
        'stuff' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un stuff.
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
      $stuff = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownStuffList;
      $stuff = reset($stuff);
    } else {
      $mode = 'create';
      $stuff = R::dispense('stuff');
      $stuff->user_id = self::$USER->id;
    }
    $total_stuffs = $stuff->id>0 ? 1 : 0;
    if($stuff) {
      $stuff->import( $data, 'name' );
      R::store( $stuff );
      self::json([
        'total_stuffs' => $total_stuffs,
        'mode' => $mode,
        'stuff' => [
          'id' => $stuff->id,
          'name' => $stuff->name,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_stuffs' => 0,
        'stuff' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}