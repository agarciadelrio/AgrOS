<?php

/**
 * Controlador para la gestión de Farms.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class FarmsController extends SessionController {

  /**
   * Devuelve el listado de farms.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_farms = self::$USER->countOwn('farm');
    $fields = [
      'id',
      'name',
      'active',
    ];
    $fields = implode(',', $fields);
    $farms = R::getAll("SELECT $fields FROM farm WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_farms' => $total_farms,
      'farms' => $farms,
    ]);
  }

  /**
   * Devuelve los valores de un farm.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $farm = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownFarmList;
    if($farm) {
      $farm = reset($farm);
      $total_farms = $farm ? 1:0;
      self::json([
        'total_farms' => $total_farms,
        'farm' => [
          'id' => $farm->id,
          'name' => $farm->name,
          'active' => $farm->active,
          'user_id' => $farm->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_farms' => 0,
        'farm' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un farm.
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
      $farm = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownFarmList;
      $farm = reset($farm);
    } else {
      $mode = 'create';
      $farm = R::dispense('farm');
      $farm->user_id = self::$USER->id;
    }
    $total_farms = $farm->id>0 ? 1 : 0;
    if($farm) {
      $fields = [
        'name',
        'active',
      ];
      $fields = implode(',', $fields);
      $farm->import( $data, $fields );
      R::store( $farm );
      self::json([
        'total_farms' => $total_farms,
        'mode' => $mode,
        'farm' => [
          'id' => $farm->id,
          'name' => $farm->name,
          'active' => $farm->active,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_farms' => 0,
        'farm' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}