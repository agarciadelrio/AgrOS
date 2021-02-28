<?php

/**
 * Usuarios.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class UserController extends Controller {
  static $TABLE_NAME = 'user';
  static $TABLE_COLUMNS = [
    'name',
    'lastname1',
    'lastname2',
    'email',
    'active',
    'admin',
    'vat',
    'email',
    'street',
    'street2',
    'zip',
    'city',
    'state',
    'country_code',
    'mobile',
    'phone',
  ];

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