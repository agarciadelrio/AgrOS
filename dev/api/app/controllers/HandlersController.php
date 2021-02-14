<?php

/**
 * Controlador para la gestión de Handlers.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class HandlersController extends SessionController {

  /**
   * Devuelve el listado de handlers.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_handlers = self::$USER->countOwn('handler');
    $fields = [
      'id',
      'name',
      'code',
      'vat',
    ];
    $fields = implode(',', $fields);
    $handlers = R::getAll("SELECT $fields FROM handler WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_handlers' => $total_handlers,
      'handlers' => $handlers,
    ]);
  }

  /**
   * Devuelve los valores de un handler.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $handler = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownHandlerList;
    if($handler) {
      $handler = reset($handler);
      $total_handlers = $handler ? 1:0;
      self::json([
        'total_handlers' => $total_handlers,
        'handler' => [
          'id' => $handler->id,
          'name' => $handler->name,
          'code' => $handler->code,
          'vat' => $handler->vat,
          'description' => $handler->description,
          'user_id' => $handler->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_handlers' => 0,
        'handler' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un handler.
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
      $handler = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownHandlerList;
      $handler = reset($handler);
    } else {
      $mode = 'create';
      $handler = R::dispense('handler');
      $handler->user_id = self::$USER->id;
    }
    $total_handlers = $handler->id>0 ? 1 : 0;
    if($handler) {
      $fields = [
        'name',
        'code',
        'vat',
        'description',
      ];
      $fields = implode(',', $fields);
      $handler->import( $data, $fields );
      R::store( $handler );
      self::json([
        'total_handlers' => $total_handlers,
        'mode' => $mode,
        'handler' => [
          'id' => $handler->id,
          'name' => $handler->name,
          'code' => $handler->code,
          'vat' => $handler->vat,
          'description' => $handler->description,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_handlers' => 0,
        'handler' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}