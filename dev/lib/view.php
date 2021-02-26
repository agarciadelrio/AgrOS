<?php
/**
 * View es la clase base para las vistas
 *
 * @package AgrOS
 * @subpackage Lib
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class View {
  /**
   * Datos que se pasan a la clase.
   *
   * @var array
   */
  public static $data = [
    'body' => ''
  ];

  /**
   * Cuando se llama a una variable estática no definida la define e inicializa.
   *
   * @param mixed $name
   * @param mixed $arguments=null
   *
   * @return string|null
   */
  public static function __callStatic($name, $arguments=null) {
    if($arguments) {
      if (!array_key_exists($name, self::$data)) {
        self::$data[$name] = '';
      }
      self::$data[$name] .= $arguments[0];
    } else {
      if (array_key_exists($name, self::$data)) {
        return self::$data[$name];
      }
      return null;
    }
  }

  /**
   * Comprueba si existe $name como propiedad estática de la clase.
   *
   * @param mixed $name
   *
   * @return bool
   */
  public static function exists($name) {
    return array_key_exists($name, self::$data);
  }

  /**
   * Setter: Establece un valor para una propiedad estática.
   *
   * @param string $name Nombre de la propiedad.
   * @param mixed $value Valor de la propiedad.
   *
   * @return void
   */
  public function __set($name, $value) {
    self::$data[$name] = $value;
  }

  /**
   * Getter: Devuelve el valor de una propiedad estática.
   *
   * @param string $name Nombre de la propiedad.
   *
   * @return mixed|null El valor de la propiedad.
   */
  public function __get($name) {
    if (array_key_exists($name, self::$data)) {
      return self::$data[$name];
    }

    $trace = debug_backtrace();
    trigger_error(
        'Undefined property via __get(): ' . $name .
        ' in ' . $trace[0]['file'] .
        ' on line ' . $trace[0]['line'],
        E_USER_NOTICE);
    return null;
  }

}

?>
