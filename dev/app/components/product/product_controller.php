<?php

/**
 * [Description ProductController]
 */
class ProductController extends Controller {

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    $user = Sessions::authenticate();
    self::render('product/_index', $params);
  }
}