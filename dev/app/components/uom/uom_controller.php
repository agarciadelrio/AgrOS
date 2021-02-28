<?php

/**
 * [Description UomController]
 */
class UomController extends Controller {

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    $user = Sessions::authenticate();
    self::render('uom/_index', $params);
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function load($params=[]) {
    $user = Sessions::authenticate();
    $uoms = array_values(R::find('uom'));
    self::json([
      'msg' => 'load',
      'uoms' => $uoms,
    ]);
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function create($params=[]) {
    $user = Sessions::authenticate();
    $json = file_get_contents('php://input');
    $uom_data = json_decode($json);
    $uom = R::dispense('uom');
    $uom->import($uom_data, 'abbr,factor,name,symbol');
    $uom->user = $user;
    R::store($uom);
    self::json([
      'msg' => 'UOM CREATE',
      'uom' => $uom,
      'uom_data' => $uom_data,
    ]);
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function update($params=[]) {
    $user = Sessions::authenticate();
    $json = file_get_contents('php://input');
    $uom_data = json_decode($json);
    $uom = R::load('uom', $params['id']);
    if($uom) {
      $uom->import($uom_data, 'abbr,factor,name,symbol');
      $uom->user = $user;
      R::store($uom);
      self::json([
        'msg' => 'UOM UPDATE',
        'uom' => $uom,
        'uom_data' => $uom_data,
      ]);
    } else throw new Exception("UOM {$params['id']} NO ENCONTRADO.");
  }
}