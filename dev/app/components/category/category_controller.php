<?php

class CategoryController extends Controller {
  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
      self::render('category/_index',$params);
      //self::json($params);
  }
}