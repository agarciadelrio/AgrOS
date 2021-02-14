<?php

/**
 * Controlador para la gestión de Profiles.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class ProfilesController extends SessionController {

  /**
   * Devuelve el listado de profiles.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_profiles = self::$USER->countOwn('profile');
    $profiles = R::getAll("SELECT id, name FROM profile WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_profiles' => $total_profiles,
      'profiles' => $profiles,
    ]);
  }

  /**
   * Devuelve los valores de un profile.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $profile = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownProfileList;
    if($profile) {
      $profile = reset($profile);
      $total_profiles = $profile ? 1:0;
      self::json([
        'total_profiles' => $total_profiles,
        'profile' => [
          'id' => $profile->id,
          'name' => $profile->name,
          'user_id' => $profile->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_profiles' => 0,
        'profile' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un profile.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function post($params=[]) {
    self::check_api_token();
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $id = intval($params['id']);
    if($id>0) {
      $mode = 'update';
      $profile = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownProfileList;
      $profile = reset($profile);
    } else {
      $mode = 'create';
      $profile = R::dispense('profile');
      $profile->user_id = self::$USER->id;
    }
    $total_profiles = $profile->id>0 ? 1 : 0;
    if($profile) {
      $profile->import( $data, 'name' );
      R::store( $profile );
      self::json([
        'total_profiles' => $total_profiles,
        'mode' => $mode,
        'profile' => [
          'id' => $profile->id,
          'name' => $profile->name,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_profiles' => 0,
        'profile' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}