<?php

/**
 * [Description UserController]
 */
class UserController extends Controller {

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    $user = Sessions::authenticate();
    self::render('user/_index', $params);
  }
}