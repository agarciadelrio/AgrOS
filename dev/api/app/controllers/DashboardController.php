<?php
/**
 * Controlador para gestionar el panel de control del usuario.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class DashboardController extends SessionController {


  /**
   * Muestra los datos principales para el panel de control.
   *
   * @param mixed $params=[]
   *
   * @return void
   */
  public static function index($params=[]) {
    self::check_api_token();
    self::authenticate();
    $user = R::load('user', $_SESSION['user']['id']);
    $params = [
      'user' => $user,
      'properties' => $user->ownPropertyList,
      'actions' => [
        '/maintenance/add' => _t('Maintenance'),
        '/soilworks/add' => _t('Soil works'),
        '/irrigations/add' => _t('Irrigation'),
        '/planting/add' => _t('Planting'),
        '/harvest/add' => _t('Harvest'),
        '/analysis/add' => _t('Analysis'),
      ],
    ];
    self::render('dashboard/index', $params);
  }
}
?>