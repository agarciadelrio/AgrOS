<?php

/**
 * Controlador para la gestión de Properties.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class PropertiesController extends SessionController {

  /**
   * Devuelve el listado de properties.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_properties = self::$USER->countOwn('property');
    $fields = [
      'id',
      'name',
      'active',
      'register_code',
    ];
    $fields = implode(',', $fields);
    $properties = R::getAll("SELECT $fields FROM property WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_properties' => $total_properties,
      'properties' => $properties,
    ]);
  }

  /**
   * Devuelve los valores de un property.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $property = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownPropertyList;
    if($property) {
      $property = reset($property);
      $total_properties = $property ? 1:0;
      self::json([
        'total_properties' => $total_properties,
        'property' => [
          'id' => $property->id,
          'name' => $property->name,
          'active' => $property->active,
          'register_code' => $property->register_code,
          'description' => $property->description,
          'latitude' => $property->latitude,
          'longitude' => $property->longitude,
          'altitude' => $property->altitude,
          'user_id' => $property->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_properties' => 0,
        'property' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un property.
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
      $property = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownPropertyList;
      $property = reset($property);
    } else {
      $mode = 'create';
      $property = R::dispense('property');
      $property->user_id = self::$USER->id;
    }
    $total_properties = $property->id>0 ? 1 : 0;
    if($property) {
      $fields = [
        'name',
        'active',
        'register_code',
        'description',
        'latitude',
        'longitude',
        'altitude',
      ];
      $fields = implode(',', $fields);
      $property->import( $data, $fields );
      R::store( $property );
      self::json([
        'total_properties' => $total_properties,
        'mode' => $mode,
        'property' => [
          'id' => $property->id,
          'name' => $property->name,
          'active' => $property->active,
          'register_code' => $property->register_code,
          'description' => $property->description,
          'latitude' => $property->latitude,
          'longitude' => $property->longitude,
          'altitude' => $property->altitude,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_properties' => 0,
        'property' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}