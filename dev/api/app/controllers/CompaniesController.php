<?php

/**
 * Controlador para la gestión de Companies.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class CompaniesController extends SessionController {

  /**
   * Devuelve el listado de companies.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_companies = self::$USER->countOwn('company');
    $companies = R::getAll("SELECT id, name FROM company WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_companies' => $total_companies,
      'companies' => $companies,
    ]);
  }

  /**
   * Devuelve los valores de un company.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $company = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownCompanyList;
    if($company) {
      $company = reset($company);
      $total_companies = $company ? 1:0;
      self::json([
        'total_companies' => $total_companies,
        'company' => [
          'id' => $company->id,
          'name' => $company->name,
          'user_id' => $company->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_companies' => 0,
        'company' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un company.
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
      $company = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownCompanyList;
      $company = reset($company);
    } else {
      $mode = 'create';
      $company = R::dispense('company');
      $company->user_id = self::$USER->id;
    }
    $total_companies = $company->id>0 ? 1 : 0;
    if($company) {
      $company->import( $data, 'name' );
      R::store( $company );
      self::json([
        'total_companies' => $total_companies,
        'mode' => $mode,
        'company' => [
          'id' => $company->id,
          'name' => $company->name,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_companies' => 0,
        'company' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}