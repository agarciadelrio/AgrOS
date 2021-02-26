<?php

class UserController extends Controller {

  public static function index($params=[]) {
    self::render('user/_index', $params);
  }
}