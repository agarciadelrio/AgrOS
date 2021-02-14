<?php

/**
 * Controlador para la gestión de Teams.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class TeamsController extends SessionController {

  /**
   * Devuelve el listado de teams.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_teams = self::$USER->countOwn('team');
    $fields = [
      'id',
      'name',
      'personal_team',
    ];
    $fields = implode(',', $fields);
    $teams = R::getAll("SELECT $fields FROM team WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_teams' => $total_teams,
      'teams' => $teams,
    ]);
  }

  /**
   * Devuelve los valores de un team.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $team = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownTeamList;
    if($team) {
      $team = reset($team);
      $total_teams = $team ? 1:0;
      self::json([
        'total_teams' => $total_teams,
        'team' => [
          'id' => $team->id,
          'name' => $team->name,
          'personal_team' => $team->personal_team,
          'user_id' => $team->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_teams' => 0,
        'team' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un team.
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
      $team = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownTeamList;
      $team = reset($team);
    } else {
      $mode = 'create';
      $team = R::dispense('team');
      $team->user_id = self::$USER->id;
    }
    $total_teams = $team->id>0 ? 1 : 0;
    if($team) {
      $fields = [
        'name',
        'personal_team',
      ];
      $fields = implode(',', $fields);
      $team->import( $data, $fields );
      R::store( $team );
      self::json([
        'total_teams' => $total_teams,
        'mode' => $mode,
        'team' => [
          'id' => $team->id,
          'name' => $team->name,
          'personal_team' => $team->personal_team,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_teams' => 0,
        'team' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}