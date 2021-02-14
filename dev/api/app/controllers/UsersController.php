<?php
/**
 * Controlador para gestionar Usuarios.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class UsersController extends SessionController {

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_users =  R::count('user');
    $users =  R::getAll("SELECT id, name, email FROM user");

    self::json([
      'total_users' => $total_users,
      'users' => $users,
    ]);
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function get($params=[]) {
    self::check_api_token();
    $user =  R::load('user', $params['id']);
    $total_users =  $user->id>0 ? 1 : 0;
    self::json([
      'total_users' => $total_users,
      'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
      ],
    ]);
  }

  public static function post($params=[]) {
    self::check_api_token();
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $id = intval($params['id']);
    if($id>0) {
      $mode = 'update';
      $user =  R::load('user', $id);
    } else {
      $mode = 'create';
      $user = R::dispense('user');
    }
    $total_users =  $user->id>0 ? 1 : 0;
    $user->import( $data, 'name,email' );
    $user->user_id = 1;
    R::store( $user );
    self::json([
      'total_users' => $total_users,
      'mode' => $mode,
      'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
      ],
      'data' => $data,
    ]);
  }

  public static function login($params=[]) {
    $email = $params['email'];
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $password = $params['password'];
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
        $_SESSION['user'] = [
          'id' => $user->id,
          'email' => $user->email,
        ];
        self::redirect('/dashboard',[
          'msg' => _t('User login successful'),
        ]);
        self::json([
          'msg' => 'LOGIN',
          'match' => $match,
          'user' => $user,
          'password' => $password,
          'password_verify' => $password_verify,
          'params' => $params,
        ]);
      } else {
        session_unset();
        session_destroy();
        self::json([
          'msg' => 'LOGIN ERROR',
        ]);
      }
    } else {
      session_unset();
      session_destroy();
      self::json([
        'msg' => 'INVALID EMAIL',
      ]);
    }
  }

  public static function remember_password($params=[]) {
    self::json([
      'msg' => 'REMEMBER PASSWORD',
    ]);
  }

  public static function register($params=[]) {
    $string_validation = [
      "filter"=>FILTER_CALLBACK,
      "flags"=>FILTER_FORCE_ARRAY,
      "options"=>"ucwords"
    ];
    $validate = [
      'email' => FILTER_VALIDATE_EMAIL,
      'password' => FILTER_SANITIZE_STRING,
      'password_confirm' => FILTER_SANITIZE_STRING,
      'accept' => FILTER_VALIDATE_BOOLEAN,
    ];
    $filter = filter_input_array(INPUT_POST, $validate);


    $confirm = isset($filter['password']) && $filter['password'] && $filter['password']===$filter['password_confirm'];
    $filter['confirm'] = $confirm;

    $invalid = in_array(false, $filter, true);
    if($invalid) {
      $params = [
        'email' => $filter['email'],
      ];
      View::notification('Se ha producido un error al intentar validar los datos del formulario.');
      self::render('home/register', $params);
    } else {
      $user = R::dispense( 'user' );
      $user->email = $filter['email'];
      $user->password = $filter['password'];
      $time = R::isoDateTime();
      $user->created_at = $time;
      $user->updated_at = $time;
      try {
        $id = R::store( $user );
        self::render('users/register', $params);
      } catch (Exception $ex) {
        $msg = $ex->getMessage();
        if(substr($msg,0,15)==='SQLSTATE[23000]') {
          $msg = _t('User email already exists. Please try with a different one.');
        }
        $params['msg'] = $msg;
        View::notification($msg);
        $params = [
          'email' => $filter['email'],
        ];
        self::render('home/register', $params);
      }


      #self::json([
      #  'msg' => $msg ?? 'USER REGISTERED',
      #  '_POST' => $_POST,
      #  'filter' => $filter,
      #  'confirm' => $confirm,
      #  'invalid' => $invalid,
      #]);
    }
  }

}