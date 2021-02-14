<?php
/**
 * Controlador para gestionar el panel de control del usuario.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class PropertiesController extends SessionController {

  protected static function initDataForRedBean($user) {
    /* PROPERTY
    $property = R::dispense('property');
    $property->name = 'Finca El Garabato';
    $property->register_code = 'NRGC:012345678';
    $property->description = 'Se detalla el carácter de la explotación';
    $property->farm_sketch_svg = '<?xml version="1.0" encoding="utf-8"?>
    <svg viewBox="0 0 500 500" width="500" height="500">
    <rect x="0" y="0" width="500" height="500" rx="15" ry="15" style="fill: rgb(216, 216, 216);"></rect>
    </svg>';
    $property->latitude = 0.123;
    $property->longitude = 0.123;
    $property->altitude = 0.123;

    $user->ownPropertyList[] = $property;
    R::store( $user );
    */

    // A.3 Descripción general de la explotación. Has many handlers
    // A.4 Descripción explotación agrícola. Has many units
    // A.5 Identificación de superficies agrícolas. Has many plots SIGPAC
    // A.8 Análisis de agua de riego. Has many water analysis
    // A.9 Análisis de suelo. Has many soil analysis
  }

  /**
   * @return User
   */
  protected static function before_action() {
    self::authenticate();
    return R::load( 'user', $_SESSION['user']['id'] );
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    $user = self::before_action();
    $params['user'] = $user;
    self::render('properties/index', $params);
  }

  /**
   * Añade una nueva propiedad.
   *
   * @param mixed $params=[]
   *
   * @return void
   */
  public static function add($params=[]) {
    self::json([
      'msg' => 'ADD PROPERTY'
    ]);
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function edit($params=[]) {
    $user = self::before_action();
    $params['user'] = $user;
    $user->withCondition('id=?',[$params['id']])->ownPropertyList;
    self::render('properties/form', $params);
  }

  public static function sketch($params=[]) {
    $user = self::before_action();
    self::json($params);
  }

  public static function hasmany($params=[]) {
    $user = self::before_action();
    $type = $params['bean'];
    if(array_key_exists($type, Property::types())) {
      $bean = Property::types()[$type]['bean'] ?? $type;
      $params['bean'] = $bean;
      $params['type'] = Property::types()[$type];
      $property = R::findOne( 'property', 'user_id=?', [ $params['id'] ]);
      if($property) {
        $ownList = 'own' . ucfirst($bean) . 'List';
        $params['items'] = $property->$ownList;
        $id = $user->id;
        $params['url'] = "/properties/$id/hasmany/$bean";
        $params['property'] = $property;
        #self::json($params);
        self::render('properties/hasmany', $params);
      } else {
        self::not_found();
      }
    } else {
      self::not_found();
    }
  }

  public static function add_hasmany($params=[]) {
    $user = self::before_action();
    $type = $params['bean'];
    if(array_key_exists($type, Property::types())) {
      $bean = Property::types()[$type]['bean'] ?? $type;
      $params['bean'] = $bean;
      $params['type'] = Property::types()[$type];
      self::render('properties/hasmany_form', $params);
      #self::json($params);
    } else {
      self::not_found();
    }
  }

  public static function create_hasmany($params=[]) {
    $user = self::before_action();
    $property = R::findOne( 'property', 'user_id=? AND id=?', [ $user->id, $params['id'] ]);
    if($property) {
      $bean_name = $params['bean'];
      if(array_key_exists($bean_name, Property::types())) {
        $model = ucfirst($bean_name);
        $filter = $model::validation();
        #self::json([$_POST, $filter]); die;
        if($filter) {
          $filter['user'] = [
            '_type' => 'user',
            'id' => $user->id,
          ];
          $time = R::isoDateTime();
          if($params['item_id']==0) {
            // CREAR EL NUEVO ITEM
            $msg = 'ITEM CREATED';
            $filter['created_at'] = $time;
            $filter['property_id'] = $property->id;
            $bean = R::dispense($bean_name);
          } else {
            // ACTUALIZAR EL ITEM SELECCIONADO
            $msg = 'ITEM UPDATED';
            $bean = R::findOne(
              $bean_name,
              'user_id=? AND property_id=? AND id=?',
              [$user->id, $params['id'], $params['item_id']]
            );
          }
          if($bean) {
            $filter['updated_at'] = $time;
            $bean->import($filter);
            #self::json([$bean, $params, $user, $filter]); die;
            R::store($bean);
            $id = $user->id;
            self::redirect(
              "/properties/$id/hasmany/$bean_name",
              ['msg' => $msg]
            );
          } else {
            self::not_found();
          }
        } else {
          self::params_error();
        }
      } else {
        self::not_found();
      }
    } else {
      self::not_found();
    }
  }

  public static function hasmany_edit($params=[]) {
    $user = self::before_action();
    $type = $params['bean'];
    if(array_key_exists($type, Property::types())) {
      //$property = $user->withCondition('id=?',[$params['id']])->ownPropertyList;
      $property = R::findOne( 'property', 'user_id=?', [ $params['id'] ]);
      if($property) {
        $bean = Property::types()[$type]['bean'] ?? $type;
        $params['bean'] = $bean;
        $params['type'] = Property::types()[$type];
        $ownList = 'own' . ucfirst($bean) . 'List';
        $item = R::findOne( $bean, 'user_id=? AND property_id=? AND id=?',[$user->id, $params['id'], $params['item_id']]);
        if($item) {
          $params['item'] = $item;
          self::render('properties/hasmany_form', $params);
          #self::json([$property, $item, $params]);
        } else {
          #self::json([$property, $item, $params]);
          self::not_found();
        }
      } else {
        self::not_found();
      }
    } else {
      self::not_found();
    }
  }
}
?>