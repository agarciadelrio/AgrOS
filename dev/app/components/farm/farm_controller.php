<?php
/**
 * Controlador para el panel de control del usuario.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class FarmController extends Controller {
  public static function index($params=[]) {
    $user = Sessions::authenticate();
    $params['ses'] = json_encode($_SESSION);
    $params['user'] = $user;
    self::render('farm/_index', $params);
  }

  public static function get($params=[]) {
    $user = Sessions::authenticate();
    $params['ses'] = json_encode($_SESSION);
    $params['user'] = $user;
    $params['farm'] = R::load('farm', $params['id']);
    self::render('farm/_get', $params);
  }
}