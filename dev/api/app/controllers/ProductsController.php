<?php

/**
 * Controlador para la gestión de Products.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class ProductsController extends SessionController {

  /**
   * Devuelve el listado de products.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_products = self::$USER->countOwn('product');
    $products = R::getAll("SELECT id, name FROM product WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_products' => $total_products,
      'products' => $products,
    ]);
  }

  /**
   * Devuelve los valores de un product.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $product = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownProductList;
    if($product) {
      $product = reset($product);
      $total_products = $product ? 1:0;
      self::json([
        'total_products' => $total_products,
        'product' => [
          'id' => $product->id,
          'name' => $product->name,
          'user_id' => $product->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_products' => 0,
        'product' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un product.
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
      $product = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownProductList;
      $product = reset($product);
    } else {
      $mode = 'create';
      $product = R::dispense('product');
      $product->user_id = self::$USER->id;
    }
    $total_products = $product->id>0 ? 1 : 0;
    if($product) {
      $product->import( $data, 'name' );
      R::store( $product );
      self::json([
        'total_products' => $total_products,
        'mode' => $mode,
        'product' => [
          'id' => $product->id,
          'name' => $product->name,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_products' => 0,
        'product' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}