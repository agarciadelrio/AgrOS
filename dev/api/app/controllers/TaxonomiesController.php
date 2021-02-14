<?php

/**
 * Controlador para la gestión de Taxonomies.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class TaxonomiesController extends SessionController {

  /**
   * Devuelve el listado de taxonomies.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_taxonomies = self::$USER->countOwn('taxonomy');
    $taxonomies = R::getAll("SELECT id, name FROM taxonomy WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_taxonomies' => $total_taxonomies,
      'taxonomies' => $taxonomies,
    ]);
  }

  /**
   * Devuelve los valores de un taxonomy.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $taxonomy = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownTaxonomyList;
    if($taxonomy) {
      $taxonomy = reset($taxonomy);
      $total_taxonomies = $taxonomy ? 1:0;
      self::json([
        'total_taxonomies' => $total_taxonomies,
        'taxonomy' => [
          'id' => $taxonomy->id,
          'name' => $taxonomy->name,
          'user_id' => $taxonomy->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_taxonomies' => 0,
        'taxonomy' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un taxonomy.
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
      $taxonomy = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownTaxonomyList;
      $taxonomy = reset($taxonomy);
    } else {
      $mode = 'create';
      $taxonomy = R::dispense('taxonomy');
      $taxonomy->user_id = self::$USER->id;
    }
    $total_taxonomies = $taxonomy->id>0 ? 1 : 0;
    if($taxonomy) {
      $taxonomy->import( $data, 'name' );
      R::store( $taxonomy );
      self::json([
        'total_taxonomies' => $total_taxonomies,
        'mode' => $mode,
        'taxonomy' => [
          'id' => $taxonomy->id,
          'name' => $taxonomy->name,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_taxonomies' => 0,
        'taxonomy' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}