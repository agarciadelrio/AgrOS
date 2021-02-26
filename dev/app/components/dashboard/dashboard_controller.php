<?php
/**
 * Controlador para el panel de control del usuario.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class DashboardController extends Controller {
  public static function index($params=[]) {
    $user = Sessions::authenticate();
    $params['ses'] = json_encode($_SESSION);
    $params['user'] = $user;
    self::render('dashboard/_index', $params);
  }

  public static function profile($params=[]) {
    $user = Sessions::authenticate();
    $params['ses'] = json_encode($_SESSION);
    $params['user'] = $user;
    self::render('dashboard/_profile', $params);
  }

  public static function save_profile($params=[]) {
    $user = Sessions::authenticate();
    extract(obj2dict($params, Profile::$input));
    // comprobar email válido
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
      throw new Exception('El formato del email no es correcto.');
    // comprobar que el email no existe
    if($user->email!=$email) {
      $u = R::findOne('user', 'id<>? AND email=?',[
        [$user->id,PDO::PARAM_INT],
        [$email,PDO::PARAM_STR],
      ]);
      if($u) {
        self::redirect('/profile',['msg' => "Atención!!! No se han podido guardar los cambios."
        ."<br/>El email <strong>$email</strong> ya está registrado."]);
      }
    }
    foreach(Profile::$input as $fld) {
      $user[$fld] = trim($$fld);
    }
    R::store($user);
    self::redirect('/profile',['msg' => "Los cambios han sido guardados correctamente."]);
    self::json([
      'params' => $params,
      '_SESSION' => $_SESSION,
      'user' => $user,
      'lastname1' => $lastname1,
    ]);
  }
}
