<?php

/**
 * Sesiones
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class SessionController extends Controller{
  static $layout = 'minimal';

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function login($params) {
    //$user = obj2dict($params['session'],'email,password,dni');
    // comprobar user y passwd
    $email = $params['session']['email'];
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      // localiza al usuario
      $user = R::findOne( 'user', 'email = ?',[[ $email , \PDO::PARAM_STR ]] );
      // Comprueba si el email está verificado
      if(!$user->email_verified_at) {
        Sessions::destroy();
        throw new Exception(
          "Todavía no ha verificado <strong>$email</strong>.<br/>".
          "Revisa tu correo electrónico, o solicite<br/>".
          "<h3><a href='/resend-register?email=" . urlencode($email). "'>reenviar email de verificación</a>.</h3>"
        );
      }
      if($user) {
        $password = $params['session']['password'];
        $password_verify = password_verify($password, $user->password);
      } else throw new Exception("No existe el usuario <strong>$email</strong>.");
      // Comprueba si la contraseña es correcta
      if($password_verify) {
        $user->remember_token = NULL;
        $user->last_login_at = R::isoDateTime();
        R::store($user);
        $_SESSION['user'] = [
          'id' => $user->id,
          'email' => $user->email,
          'active' => $user->active,
          'admin' => $user->admin,
          'owner' => $user->owner,
        ];
        self::redirect('/dashboard',[
          'msg' => _t('User login successful'),
        ]);
        //self::json([
        //  'msg' => 'LOGIN',
        //  'match' => $match,
        //  'user' => $user,
        //  'password' => $password,
        //  'password_verify' => $password_verify,
        //  'params' => $params,
        //]);
      } else {
        Sessions::destroy();
        throw new Exception("Contraseña para <strong>$email</strong> inválida.");
      }
    } else {
      Sessions::destroy();
      throw new Exception('Formato de correo electrónico inválido.');
    }
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function recover($params) {
    self::$layout = 'minimal';
    Sessions::destroy();
    $email = filter_var($params['session']['email']??'', FILTER_VALIDATE_EMAIL);
    // VALIDA EMAIL
    if(!$email) throw new Exception('Formato de email inválido.');
    // busca un usuario con ese email
    $user = R::findOne('user', 'email = ?', [[$email, PDO::PARAM_STR]]);
    // Si no encuentra usuario con ese email devuelve excepción
    if(!$user) throw new Exception("Usuario $email no encontrado.<br/><a href='/register'>Regístrate</a>");
    // genera un token para la recuperación
    $user->remember_token =  uniqid();
    try {
      R::store($user);
    } catch (\Throwable $th) {
      throw new Exception('No hemos podido actualizar la cuenta. ' . $th->getMessage());
    }
    // envía en email al usuario para recuperar la contraseña
    $to = $user->email;
    $subject = subjectUtf8('[AgrOS]: recuperación de acceso');
    $params['url'] = SITE_URL . '/reset-password/' . $user->remember_token;
    $body = self::partial('mail','mails/reset-password', $params);
    $mail_response = self::mail($to, $subject, $body);
    if(!$mail_response) {
      throw new Exception('No hemos podido enviar el correo de registro a ' . $user->email);
    }
    $params['title'] = 'RECOVER';
    $params['message'] = "Te hemos enviado un email de confirmación con "
      . "las instrucciones para recuperar su acceso a la aplicación.";
    self::render('home/_mail_sent', $params);
    //self::json([
    //  'user' => $user,
    //  'email' => $email,
    //  'params' => $params,
    //  'body' => $body,
    //  'mail_conf' => [$to, $subject, $body],
    //  'mail_response' => $mail_response,
    //]);
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function register($params) {
    self::$layout = 'minimal';
    $email = $params['session']['email'];
    // VALIDA EMAIL
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      throw new Exception('El formato del email no es correcto.');
    };
    $password = $params['session']['password'];
    if($password != $params['session']['password_confirm']) {
      throw new Exception('La confirmación de la contraseña no es correcta.');
    }
    // BUSCAR SI YA EXISTE EL USUARIO
    $_user = R::find('user','email=?',[$email]);
    // SI YA EXISTE -> mensaje de error. FIN.
    if($_user) {
      throw new Exception(
        "Lo sentimos, el usario con email <strong>$email</strong> ya existe." .
        "<br/>Intente acceder mediante el <a href='/login'>login</a>, " .
        "solicite <a href='/recover'>reestablecer</a> contraseña" .
        "o registre un nuevo usuario <a href='/register'>aquí</a>."
      );
    }
    // SI NO EXISTE -> crear nueva cuenta y poner en modo inactivo
    $_user = R::dispense('user');
    $_user->name = '';
    $_user->email = $email;
    $_user->active = FALSE;
    $_user->user_id = 1;
    $_user->admin = 0;
    $_user->email_verified_at = NULL;
    // GENERAR TOKEN DE ACCESO
    $_user->remember_token = uniqid();
    $_user->password = password_hash($password, PASSWORD_ARGON2I);
    $_user->created_at = R::isoDateTime();
    $_user->updated_at = R::isoDateTime();

    try {
      R::store($_user);
    } catch (\Throwable $th) {
      throw new Exception('No hemos podido crear la cuenta. ' . $th->getMessage());
    }

    // Enviar email al usuario
    $to = $_user->email;
    $subject = '[AgrOS]: registro de usuario';
    $params['url'] = SITE_URL . '/active-account/' . $_user->remember_token;
    $body = self::partial('mail','mails/register-ok', $params);
    if(!self::mail($to, $subject, $body)) {
      throw new Exception('No hemos podido enviar el correo de registro a ' . $_user->email);
    }
    // Enviar email al admin
    // Notificar al admin para validar cuenta
    $params['title'] = 'REGISTER';
    $params['message'] = "<strong>Hola, acabamos de recibir tu solicitud de registro.<br/>"
      . "Te hemos enviado un email de confirmación a $email con las instrucciones para que puedas acceder a la aplicación.</strong>";
    self::render('home/_mail_sent', $params);
    //self::json([
    //  'msg' => 'register',
    //  'params' => $params,
    //  '$_user' => $_user,
    //]);
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function get_tasks($params) {
    $user = Sessions::authenticate();
    $limit = $params['limit'] ?? 10;
    $order = $params['order'] ?? '';
    $order = str_replace(':', ' ', $order);
    $order = str_replace('1', 'ASC', $order);
    $order = str_replace('2', 'DESC', $order);
    if($order) $order = " ORDER BY $order ";
    $page = $params['page'] ?? 1;
    $page -=1;
    if($page<0) $page=0;
    $offset = $page * $limit;
    $total = $user->countOwn('task');
    $tasks = array_map(function($f) {
      return obj2dict($f->export(),'id,name,date,time');
    }, array_values($user->with(" $order LIMIT $limit OFFSET $offset ")->ownTaskList));
    self::json([
      'msg' => 'get_tasks',
      'params' => $params,
      'tasks' => $tasks,
      'total' => $total,
      'order' => $order,
    ]);
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function active_account($params) {
    self::$layout = 'minimal';
    // comprobar si existe una cuenta con el mismo remember token
    $token = $params['token'];
    $u = R::find('user','remember_token=?',[$token]);
    if(!$u) {
      throw new Exception('El token de acceso no es válido.<br/>Compruebe su email.');
    }
    self::render('home/_active_account', $params);
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function verify_account($params) {
    self::$layout = 'minimal';
    $email = $params['session']['email'];
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      throw new Exception('El formato del email no es correcto.');
    };
    $token = $params['session']['token'];
    $u = R::findOne('user','email=? AND remember_token=?',[
      [$email, PDO::PARAM_STR],
      [$token, PDO::PARAM_STR],
    ]);
    if(!$u) {
      throw new Exception("El email proporcionado <strong>$email</strong> no concuerda ".
      "para esta clave de validación.<br/>Compruebe su email.<br/>");
    }
    $u->emailVerifiedAt = R::isoDateTime();
    $u->rememberToken = NULL;
    R::store($u);
    self::redirect('/login',[
      'msg' => _t('Cuenta verificada.'),
    ]);
    //self::json([
    //  'msg' => 'verify_account',
    //  'params' => $params,
    //  'u' => $u,
    //]);
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function reset_password($params) {
    self::render('home/_reset_password', $params);
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function set_new_password($params) {
    $email = $params['session']['email'];
    // comprobar email válido
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception('El formato del email no es correcto.');
    }
    $password = $params['session']['password'];
    $password_confirm = $params['session']['password_confirm'];
    // si no coinciden las contraseñas
    if($password_confirm != $password_confirm) throw new Exception('Contraseñas no coinciden.');
    $token = $params['token'];
    if(!$token) throw new Exception('Token incorrecto.');
    // busca usuario
    $u = R::findOne('user', 'email = ? AND remember_token = ?',[
      [$email, PDO::PARAM_STR],
      [$token, PDO::PARAM_STR],
    ]);
    //self::json([$email, $token, $u]);die;
    if(!$u) throw new Exception('No se encuentra usuario con ese Email y Token.');
    // si toda ha ido bien entonces cambia el password y pasa al login
    $u->remember_token = NULL;
    $u->password = password_hash($password, PASSWORD_ARGON2I);
    $u->updated_at = R::isoDateTime();
    self::login([
      'session' => [
        'email' => $u->email,
        'password' => $u->password,
      ]
    ]);

    //self::render('home/_reset_password', $params);
    //self::json([
    //  'params' => $params,
    //]);
  }

  /**
   * @param mixed $params
   *
   * @return [type]
   */
  public static function resend_register($params) {
    $email = $params['email'];
    // comprobar email válido
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
      throw new Exception('El formato del email no es correcto.');

    // busca un usuario con ese email
    $user = R::findOne('user', 'email = ?', [[$email, PDO::PARAM_STR]]);
    // Si no encuentra usuario con ese email devuelve excepción
    if(!$user) throw new Exception("Usuario $email no encontrado.<br/><a href='/register'>Regístrate</a>");
    // recupera el token
    if(!$user->remember_token) throw new Exception("Token no existe.");
    // Enviar email al usuario
    $to = $user->email;
    $subject = '[AgrOS]: verificar email';
    $params['url'] = SITE_URL . '/active-account/' . $user->remember_token;
    $body = self::partial('mail','mails/register-ok', $params);
    if(!self::mail($to, $subject, $body)) {
      throw new Exception('No hemos podido enviar el correo de registro a ' . $user->email);
    }
    // Enviar email al admin
    // Notificar al admin para validar cuenta
    $params['title'] = 'VERIFICAR EMAIL';
    $params['message'] = "<strong>Hola, acabamos de recibir tu solicitud de verificación.<br/>"
      . "Te hemos enviado un email de confirmación a $email con las instrucciones para que puedas acceder a la aplicación.</strong>";
    self::render('home/_mail_sent', $params);
    //self::json($params);
  }

}