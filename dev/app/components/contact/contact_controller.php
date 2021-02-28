<?php

/**
 * Contactos.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class ContactController extends Controller {
  static $TABLE_NAME = 'contact';
  static $TABLE_COLUMNS = [
    'name','email',
    'vat','street','street2','zip',
    'city','state','country_code'
  ];
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