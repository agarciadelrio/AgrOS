<?php

/**
 * Controlador para la gestión de Permissions.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class PermissionsController extends SessionController {

  /**
   * Devuelve el listado de permissions.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_permissions = self::$USER->countOwn('permission');
    $permissions = R::getAll("SELECT id, name FROM permission WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_permissions' => $total_permissions,
      'permissions' => $permissions,
    ]);
  }

  /**
   * Devuelve los valores de un permission.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $permission = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownPermissionList;
    if($permission) {
      $permission = reset($permission);
      $total_permissions = $permission ? 1:0;
      self::json([
        'total_permissions' => $total_permissions,
        'permission' => [
          'id' => $permission->id,
          'name' => $permission->name,
          'user_id' => $permission->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_permissions' => 0,
        'permission' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un permission.
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
      $permission = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownPermissionList;
      $permission = reset($permission);
    } else {
      $mode = 'create';
      $permission = R::dispense('permission');
      $permission->user_id = self::$USER->id;
    }
    $total_permissions = $permission->id>0 ? 1 : 0;
    if($permission) {
      $permission->import( $data, 'name' );
      R::store( $permission );
      self::json([
        'total_permissions' => $total_permissions,
        'mode' => $mode,
        'permission' => [
          'id' => $permission->id,
          'name' => $permission->name,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_permissions' => 0,
        'permission' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}