<?php
/**
 * Controlador para el panel de control del usuario.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class TaskController extends Controller {
  static $TABLE_NAME = 'task';
  static $TABLE_COLUMNS = ['name'];

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    $user = Sessions::authenticate();
    self::render('task/_index', $params);
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function get($params=[]) {
    $user = Sessions::authenticate();
    $task = R::load('task', $params['id']);
    $company = $tasl->company;
    $params['ses'] = json_encode($_SESSION);
    $params['user'] = $user;
    $params['task'] = $task;
    $params['company'] = $company;
    $params['form'] = Task::$form;
    self::render('task/_get', $params);
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function get_task($params=[]) {
    $user = Sessions::authenticate();
    $id = intval($params['id']);
    $task = reset($user->with(' LIMIT 1 ')->withCondition('id=?',[$id])->ownTaskList);
    //$team1 = R::load('team', 1);
    //$team2 = R::load('team', 2);
    //$team1->sharedUserList []= $user;
    //$user->sharedTeamList []= $team1;
    //$user->sharedTeamList []= $team2;
    //$company = reset($user->ownCompanyList);
    //$task->company = $company;
    ////$task->moveType = 'in';
    //R::storeAll([$user,$team1]);
    $task['taxes'] = [2,3];
    $taxesList = [
      [ 'id' => 1, 'text' => 'IGIC 3%', 'value'  => 0.03, 'selected' => in_array(1, $task['taxes']) ],
      [ 'id' => 2, 'text' => 'IGIC 7%', 'value'  => 0.07, 'selected' => in_array(2, $task['taxes']) ],
      [ 'text' => 'Profesionales',
        'children' => [
          [ 'id' => 3, 'text' => 'IRPF -15%', 'value'  => -0.15, 'selected' => in_array(3, $task['taxes']) ],
          [ 'id' => 4, 'text' => 'IRPF -21%', 'value'  => -0.21, 'selected' => in_array(4, $task['taxes']) ],
        ]
      ]
    ];
    $companiesList = list2array($user->withCondition('active=1')->ownCompanyList, 'id,name');
    $farmsList = list2array($user->withCondition('active=1')->ownFarmList, 'id,name,company_id');
    $parcelsList = list2array($user->withCondition('active=1')->ownParcelList, 'id,name,farm_id');
    $plotsList = list2array($user->withCondition('active=1')->ownPlotList, 'id,name,parcel_id');
    $teamsList = list2array($user->sharedTeamList, 'id,name');
    $membersList = list2array(R::findAll( 'member' ),'id,name');
    $categoriesList = list2array(R::findAll( 'category' ),'id,name,category_id');
    $productsList = list2array(R::findAll( 'product'),'id,name,category_id,price');

    //$product = R::load('product',1);
    //$_task = R::load('task', 2);
    //$_task->product = $product;
    //R::store($_task);
    //$category = R::load('category',3);
    //$item->name = 'Tomate ensalada';
    //$item->category = $category;
    //R::store($item);
    //$categories[0]->name = 'Todo';
    //$categories[1]->name = 'Variedades';
    //$categories[2]->name = 'Tomate';
    //$categories[1]->ownCategory = [$categories[2]];
    //$categories[0]->ownCategory = [$categories[1]];
    //$team = R::load('team_user',1);
    //$team->role = 'admin';
    //R::storeAll($categories);
    self::json([
      'productsList' => $productsList,
      'categoriesList' => $categoriesList,
      'membersList' => $membersList,
      'teamsList' => $teamsList,
      'msg' => 'get_task',
      'user' => obj2dict($user,'id,name'),
      'plotsList' => $plotsList,
      'company' => $company,
      'companiesList' => $companiesList,
      'farmsList' => $farmsList,
      'parcelsList' => $parcelsList,
      'params' => $params,
      'task' => $task,
      'taxesList' => $taxesList,
    ]);
  }

}