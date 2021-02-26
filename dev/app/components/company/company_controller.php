<?php
/**
 * Controlador para el panel de control del usuario.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class CompanyController extends Controller {

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    $user = Sessions::authenticate();
    $params['ses'] = json_encode($_SESSION);
    $params['user'] = $user;
    self::render('company/_index', $params);
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function get($params=[]) {
    $user = Sessions::authenticate();
    $params['ses'] = json_encode($_SESSION);
    $params['user'] = $user;
    $params['company'] = R::load('company', $params['id']);
    self::render('company/_get', $params);
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function get_farms($params=[]) {
    $user = Sessions::authenticate();
    $company = R::load('company', $params['id']);
    $limit = $params['limit'] ?? 10;
    $order = $params['order'] ?? '';
    $order = str_replace(':', ' ', $order);
    $order = str_replace('1', 'ASC', $order);
    $order = str_replace('2', 'DESC', $order);
    if($order) $order = " ORDER BY $order ";
    $page = $params['page'] ?? 1;
    $page -=1;
    if($page<0) $page=0;
    $offset = $page * $limit;
    $total = $user->countOwn('task');
    $farms = array_map(function($f) {
      return obj2dict($f->export(),'id,name');
    }, array_values($company->with(" $order LIMIT $limit OFFSET $offset ")->ownFarmList));
    self::json([
      'msg' => 'get_farms',
      'params' => $params,
      'farms' => $farms,
    ]);
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function tasks_new($params=[]) {
    $user = Sessions::authenticate();
    $company = R::load('company', $params['id']);
    $taks = $company->ownTaskList;
    $params['user'] = $user;
    $params['company'] = $company;
    $params['taks'] = $taks;
    $params['form'] = Task::$form;
    self::render('company/_tasks_new', $params);
  }
}