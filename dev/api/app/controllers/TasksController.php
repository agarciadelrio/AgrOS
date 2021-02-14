<?php

/**
 * Controlador para la gestión de Tasks.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class TasksController extends SessionController {

  /**
   * Devuelve el listado de tasks.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_tasks = self::$USER->countOwn('task');
    $tasks = R::getAll("SELECT id, name FROM task WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_tasks' => $total_tasks,
      'tasks' => $tasks,
    ]);
  }

  /**
   * Devuelve los valores de un task.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $task = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownTaskList;
    if($task) {
      $task = reset($task);
      $total_tasks = $task ? 1:0;
      self::json([
        'total_tasks' => $total_tasks,
        'task' => [
          'id' => $task->id,
          'name' => $task->name,
          'user_id' => $task->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_tasks' => 0,
        'task' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un task.
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
      $task = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownTaskList;
      $task = reset($task);
    } else {
      $mode = 'create';
      $task = R::dispense('task');
      $task->user_id = self::$USER->id;
    }
    $total_tasks = $task->id>0 ? 1 : 0;
    if($task) {
      $task->import( $data, 'name' );
      R::store( $task );
      self::json([
        'total_tasks' => $total_tasks,
        'mode' => $mode,
        'task' => [
          'id' => $task->id,
          'name' => $task->name,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_tasks' => 0,
        'task' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}