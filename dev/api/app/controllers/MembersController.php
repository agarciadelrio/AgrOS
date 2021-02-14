<?php

/**
 * Controlador para la gestión de Members.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class MembersController extends SessionController {

  /**
   * Devuelve el listado de members.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_members = self::$USER->countOwn('member');
    $members = R::getAll("SELECT id, name FROM member WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_members' => $total_members,
      'members' => $members,
    ]);
  }

  /**
   * Devuelve los valores de un member.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $member = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownMemberList;
    if($member) {
      $member = reset($member);
      $total_members = $member ? 1:0;
      self::json([
        'total_members' => $total_members,
        'member' => [
          'id' => $member->id,
          'name' => $member->name,
          'user_id' => $member->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_members' => 0,
        'member' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un member.
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
      $member = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownMemberList;
      $member = reset($member);
    } else {
      $mode = 'create';
      $member = R::dispense('member');
      $member->user_id = self::$USER->id;
    }
    $total_members = $member->id>0 ? 1 : 0;
    if($member) {
      $member->import( $data, 'name' );
      R::store( $member );
      self::json([
        'total_members' => $total_members,
        'mode' => $mode,
        'member' => [
          'id' => $member->id,
          'name' => $member->name,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_members' => 0,
        'member' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}