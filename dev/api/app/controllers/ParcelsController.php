<?php

/**
 * Controlador para la gestión de Parcels.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class ParcelsController extends SessionController {

  /**
   * Devuelve el listado de parcels.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_parcels = self::$USER->countOwn('parcel');
    $fields = [
      'id',
      'name',
      'active',
    ];
    $fields = implode(',', $fields);
    $parcels = R::getAll("SELECT $fields FROM parcel WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_parcels' => $total_parcels,
      'parcels' => $parcels,
    ]);
  }

  /**
   * Devuelve los valores de un parcel.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $parcel = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownParcelList;
    if($parcel) {
      $parcel = reset($parcel);
      $total_parcels = $parcel ? 1:0;
      self::json([
        'total_parcels' => $total_parcels,
        'parcel' => [
          'id' => $parcel->id,
          'name' => $parcel->name,
          'active' => $parcel->active,
          'farm_id' => $parcel->farm_id,
          'user_id' => $parcel->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_parcels' => 0,
        'parcel' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un parcel.
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
      $parcel = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownParcelList;
      $parcel = reset($parcel);
    } else {
      $mode = 'create';
      $parcel = R::dispense('parcel');
      $parcel->user_id = self::$USER->id;
    }
    $total_parcels = $parcel->id>0 ? 1 : 0;
    if($parcel) {
      $fields = [
        'name',
        'active',
        'farm_id',
      ];
      $fields = implode(',', $fields);
      $parcel->import( $data, $fields );
      R::store( $parcel );
      self::json([
        'total_parcels' => $total_parcels,
        'mode' => $mode,
        'parcel' => [
          'id' => $parcel->id,
          'name' => $parcel->name,
          'active' => $parcel->active,
          'farm_id' => $parcel->farm_id,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_parcels' => 0,
        'parcel' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}