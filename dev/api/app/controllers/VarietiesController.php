<?php

/**
 * Controlador para la gestión de Varieties.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class VarietiesController extends SessionController {

  /**
   * Devuelve el listado de varieties.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_varieties = self::$USER->countOwn('variety');
    $varieties = R::getAll("SELECT id, name FROM variety WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_varieties' => $total_varieties,
      'varieties' => $varieties,
    ]);
  }

  /**
   * Devuelve los valores de un variety.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $variety = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownVarietyList;
    if($variety) {
      $variety = reset($variety);
      $total_varieties = $variety ? 1:0;
      self::json([
        'total_varieties' => $total_varieties,
        'variety' => [
          'id' => $variety->id,
          'name' => $variety->name,
          'user_id' => $variety->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_varieties' => 0,
        'variety' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un variety.
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
      $variety = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownVarietyList;
      $variety = reset($variety);
    } else {
      $mode = 'create';
      $variety = R::dispense('variety');
      $variety->user_id = self::$USER->id;
    }
    $total_varieties = $variety->id>0 ? 1 : 0;
    if($variety) {
      $variety->import( $data, 'name' );
      R::store( $variety );
      self::json([
        'total_varieties' => $total_varieties,
        'mode' => $mode,
        'variety' => [
          'id' => $variety->id,
          'name' => $variety->name,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_varieties' => 0,
        'variety' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}