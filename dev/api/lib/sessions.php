<?php
/** Iniciar una nueva sesión o reanudar la existente */
session_start();

/**
 * Sessions es la clase para gestionar las sesiones de los usuarios.
 *
 * @package AgrOS
 * @subpackage Lib
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class Sessions {
  /**
   * Termina la sesión si ha pasado más de 1500 sg.
   *
   * @param string $user_time_key La clave para almacenar el tiempo
   * @return bool Devuelve si ha de cerrar o no la sesión de forma automática
   */
  public static function auto_logout(string $user_time_key) {
    $t = time();
    $t0 = $_SESSION[$user_time_key] ?? $t;
    $diff = $t - $t0;
    if($diff > 1500) {
      return true;
    } else {
      $_SESSION[$user_time_key] = $t;
    }
    return false;
  }

  /**
   * Comprueba si la sesión ha caducado.
   *
   * @return void
   */
  public static function checkExpired() {
    if(self::auto_logout("user_time")) {
        session_unset();
        session_destroy();
        Controller::redirect('/session-expired',['msg' => _t('Expired session')]);
        exit;
    }
  }

  /**
   * Comprueba si el usuario está autentificado.
   *
   * @return bool Devuelve si el usuario está autentificado o no
   */
  public static function authenticate() {
    if(session_status() == PHP_SESSION_ACTIVE){
      self::checkExpired();
      if(isset($_SESSION['user'])) {
        if(isset($_SESSION['user']['id'])) {
          return True;
        }
      }
    }
    return False;
  }

  /**
   * Comprueba si puede ejecutar la acción.
   *
   * @param string $action
   *
   * @return bool
   */
  public static function canDo(string $action) {
    return True;
  }
}

/** Establece la clave para almacenar el último acceso */
Sessions::auto_logout("user_time");
?>
