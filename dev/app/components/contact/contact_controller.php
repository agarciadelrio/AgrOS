<?php

/**
 * [Description ContactController]
 */
class ContactController extends Controller {

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    $user = Sessions::authenticate();
    self::render('contact/_index', $params);
  }
}