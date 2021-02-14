<?php
/**
 * Controlador para gestionar la sesión del usuario.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class SessionController extends Controller {

  /**
   * @var User
   */
  protected static $USER = NULL;

  /**
   * Comprueba si la sesión está autenticada.
   *
   * @return void
   */
  public static function authenticate() {
    if(!Sessions::authenticate()) {
      $params['msg']  = 'Authentication required.';
      self::redirect('/login-error', $params);
    }
  }

  /**
   * @return [type]
   */
  public static function not_found() {
    $url = parse_url( $_SERVER['REQUEST_URI'] );
    $url['path'] =  rtrim( $url['path'], '/' );
    header("HTTP/1.0 404 Not Found");
    self::render('404', [
      'msg' => '"' . $url['path'] . '" not found'
    ]);
  }

  /**
   * @return [type]
   */
  public static function params_error() {
    $url = parse_url( $_SERVER['REQUEST_URI'] );
    $url['path'] =  rtrim( $url['path'], '/' );
    #header("HTTP/1.0 404 Not Found");
    #self::render('404', [
    #  'msg' => '"' . $url['path'] . '" not found'
    #]);
    self::json(['MSG' => 'Params error']);
  }

  /**
   * @return User
   */
  protected static function get_auth_user() {
    self::authenticate();
    return R::load( 'user', @$_SESSION['user']['id'] ?? 0 );
  }

  /**
   * Comprueba si el usuario existe y la Contraseña es correcta.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function login($params=[]) {
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $email = $data->email ?? FALSE;
    $password = $data->password ?? FALSE;

    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $match = R::matchUp(
        'user',
        'active = 1 AND email = ?',
        [
          [ $email , \PDO::PARAM_STR ],
        ],
        [
          'remember_token' => NULL,
          'last_login_at' => R::isoDateTime(),
        ],
        NULL,
        $user
      );
      if($user) {
        $password_verify = password_verify($password, $user->password);
      } else $password_verify = false;
      if($password_verify) {
        session_unset();
        session_destroy();
        session_start();
        // TODO: Generar API_TOKEN para esta sesión
        $api_token = 'JELOOOOOOOU';
        $_SESSION['user'] = [
          'id' => $user->id,
          'email' => $user->email,
          'api_token' => $api_token,
        ];
        self::json([
          'msg' => 'LOGIN',
          'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'api_token' => $api_token,
          ]
        ]);
        die;
      } else {
        session_unset();
        session_destroy();
        header("HTTP/1.0 401 Unauthorized");
        self::json([
          'msg' => 'LOGIN ERROR OR USER INACTIVATED BY ADMIN',
          'data' => $data,
          'email' => $email,
          '_SESSION' => $_SESSION,
          '_SERVER' => $_SERVER,
        ]);
      }
    } else {
      session_unset();
      session_destroy();
      header("HTTP/1.0 400 Bad Request");
      self::json([
        'msg' => 'EMAIL INVALID FORMAT',
        #'data' => $data,
        #'email' => $email,
        #'_SESSION' => $_SESSION,
        #'_SERVER' => $_SERVER,
      ]);
    }
  }

  public static function check_api_token() {
    try {
      self::$USER = self::get_auth_user();
    } catch (\Throwable $th) {
      self::$USER = False;
    }
    if(!self::$USER or @$_SERVER['HTTP_AUTHORIZATION']!='API_TOKEN:' . (@$_SESSION['user']['api_token'] ?? '')) {
      session_unset();
      session_destroy();
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'LOGIN ERROR',
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
      die;
    }
  }
}