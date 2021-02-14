<?php

/**
 * Controlador para la gestión de Plots.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class PlotsController extends SessionController {

  /**
   * Devuelve el listado de plots.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_plots = self::$USER->countOwn('plot');
    $fields = [
      'id',
      'name',
      'code',
    ];
    $fields = implode(',', $fields);
    $plots = R::getAll("SELECT $fields FROM plot WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_plots' => $total_plots,
      'plots' => $plots,
    ]);
  }

  /**
   * Devuelve los valores de un plot.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $plot = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownPlotList;
    if($plot) {
      $plot = reset($plot);
      $total_plots = $plot ? 1:0;
      self::json([
        'total_plots' => $total_plots,
        'plot' => [
          'id' => $plot->id,
          'name' => $plot->name,
          'code' => $plot->code,
          'description' => $plot->description,
          'surface' => $plot->surface,
          'dripper_lines' => $plot->dripper_lines,
          'dripper_gap' => $plot->dripper_gap,
          'user_id' => $plot->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_plots' => 0,
        'plot' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un plot.
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
      $plot = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownPlotList;
      $plot = reset($plot);
    } else {
      $mode = 'create';
      $plot = R::dispense('plot');
      $plot->user_id = self::$USER->id;
    }
    $total_plots = $plot->id>0 ? 1 : 0;
    if($plot) {
      $fields = [
        'name',
        'code',
        'description',
        'surface',
        'dripper_lines',
        'dripper_gap',
      ];
      $fields = implode(',', $fields);
      $plot->import( $data, $fields );
      R::store( $plot );
      self::json([
        'total_plots' => $total_plots,
        'mode' => $mode,
        'plot' => [
          'id' => $plot->id,
          'name' => $plot->name,
          'code' => $plot->code,
          'description' => $plot->description,
          'surface' => $plot->surface,
          'dripper_lines' => $plot->dripper_lines,
          'dripper_gap' => $plot->dripper_gap,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_plots' => 0,
        'plot' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}