<?php
/**
 * Model es la clase base para los modelos de datos
 *
 * @package AgrOS
 * @subpackage Lib
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class Model {
  /**
   * Campos del modelo.
   *
   * @var array
   */
  public static $fields = [];

  /**
   * Validación de los campos.
   *
   * @var array
   */
  public static $validate = [];

  /**
   * Campos requeridos
   *
   * @var array
   */
  public static $required = [];

  /**
   * Valida los valores de los campos
   *
   * @return mixed|boolean
   */
  public static function validation() {
    /** Ejecuta la validación por filtro */
    $filter = filter_input_array(INPUT_POST, static::$validate);
    $invalid = in_array(false, $filter, true);
    if($invalid) return FALSE;
    foreach(static::$required as $req) {
      if(empty($filter[$req])) return FALSE;
    }
    return $filter;
  }
}

?>