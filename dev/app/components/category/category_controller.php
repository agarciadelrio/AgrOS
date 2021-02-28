<?php

/**
 * Clase para gestionar las Categorías
 *
  * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class CategoryController extends Controller {

  /**
   * @param mixed $b
   *
   * @return [type]
   */
  public static function walking_tree($b){
    $childs = '<ul class="ps-3">';
    foreach($b->with('ORDER BY name')->ownCategory as $c) {
      $childs .= self::walking_tree($c);
    }
    $childs .= '</ul>';
    $a = "<a class=\"link-success add\" href=\"#\"><i class=\"fa fa-plus-square\"></i></a>";
    $e = "<a class=\"link-primary edit\" href=\"#\"><i class=\"fa fa-edit\"></i></a>";
    return "<li id=\"cat-{$b->id}\" data-parent=\"{$b->category_id}\"><div><span>{$b->name}</span> $e $a</div>$childs</li>";
  }

  /**
   * @param mixed $b
   * @param mixed $i=0
   *
   * @return [type]
   */
  public static function walking_html_options($b,$i=0) {
    $spaces = str_repeat('&nbsp;&nbsp;&nbsp;',$i);
    $value = $b->id;
    $label = $b->name;
    $out = "<option value=\"$value\">$spaces{$label}</option>";
    foreach($b->with('ORDER BY name')->ownCategory as $c) {
      $out .= self::walking_html_options($c, $i+1);
    }
    return $out;
  }

  /**
   * @param mixed $b
   * @param mixed $i
   * @param mixed $list
   *
   * @return [type]
   */
  public static function walking_list($b,$i,&$list) {
    $spaces = str_repeat('__',$i);
    $value = $b->id;
    $label = $b->name;
    $list []= ['id' => $value, 'text' => $spaces.' '.$label];
    foreach($b->with('ORDER BY name')->ownCategory as $c) {
      self::walking_list($c, $i+1, $list);
    }
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    $user = Sessions::authenticate();
    $category = R::findOne('category','category_id is NULL');
    $category_tree = [];
    $tree = $category->traverse( 'ownCategory', function(){});
    $params['tree'] = json_encode($tree);
    $_list = [];
    self::walking_list($tree, 0, $_list);
    $params['options_list'] = json_encode($_list);
    //$params['html_tree'] = self::walking_tree($tree);
    //$params['options_tree'] = self::walking_html_options($tree);
    self::render('category/_index',$params);
    //self::json($_list);
    ////  //'category' => $category,
    //  'tree' => $tree,
    //////  'category_tree' => $category_tree,
    //]);
    //print_r($tree);
    //die;
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function update($params=[]) {
    $user = Sessions::authenticate();
    $json = file_get_contents('php://input');
    $category_data = json_decode($json);
    $category = R::load('category', $category_data->id);
    if($category) {
      $root_category = R::findOne('category','category_id is NULL');
      $tree = $root_category->traverse( 'ownCategory', function(){});
      if($category_data->parent != $category->id) {
        $category->name = trim($category_data->name);
        $category->category_id = trim($category_data->parent);
        R::store($category);
      }
      $list = [];
      self::walking_list($tree, 0, $list);
      self::json([
        'msg' => 'update',
        'category' => $category,
        'options_tree' => self::walking_html_options($tree),
        'options_list' => $list,
      ]);
    } else {
      self::json([
        'msg' => 'update',
      ]);
    }
  }
}