<?php

class ContactController extends Controller {

  public static function index($params=[]) {
    self::render('contact/_index', $params);
  }
}