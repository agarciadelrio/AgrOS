<?php

/**
 * Controlador para la gestión de Authorizations.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class AuthorizationsController extends SessionController {

  /**
   * Devuelve el listado de authorizations.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_authorizations = self::$USER->countOwn('authorization');
    $authorizations = R::getAll("SELECT id, name FROM authorization WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_authorizations' => $total_authorizations,
      'authorizations' => $authorizations,
    ]);
  }

  /**
   * Devuelve los valores de un authorization.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $authorization = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownAuthorizationList;
    if($authorization) {
      $authorization = reset($authorization);
      $total_authorizations = $authorization ? 1:0;
      self::json([
        'total_authorizations' => $total_authorizations,
        'authorization' => [
          'id' => $authorization->id,
          'name' => $authorization->name,
          'user_id' => $authorization->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_authorizations' => 0,
        'authorization' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un authorization.
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
      $authorization = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownAuthorizationList;
      $authorization = reset($authorization);
    } else {
      $mode = 'create';
      $authorization = R::dispense('authorization');
      $authorization->user_id = self::$USER->id;
    }
    $total_authorizations = $authorization->id>0 ? 1 : 0;
    if($authorization) {
      $authorization->import( $data, 'name' );
      R::store( $authorization );
      self::json([
        'total_authorizations' => $total_authorizations,
        'mode' => $mode,
        'authorization' => [
          'id' => $authorization->id,
          'name' => $authorization->name,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_authorizations' => 0,
        'authorization' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}