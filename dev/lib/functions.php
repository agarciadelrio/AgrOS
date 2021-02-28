<?php
/**
 * Funciones comunes para toda la aplicación.
 *
 * @package AgrOS
 * @subpackage Functions
 */

/**
 * Obtine el URL del Sitio.
 *
 * @package AgrOS
 * @subpackage Functions
 * @return string La URL del Sitio
 */
function getSiteUrl() {
  // Determina el protocolo
  if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $url = "https://";
  else
    $url = "http://";
  // Append the host(domain name, ip) to the URL.
  $url.= $_SERVER['HTTP_HOST'];

  return $url;
}

/**
 * @param mixed $msg
 *
 * @return [type]
 */
function _t($msg) {
  return TRANSLATIONS[$msg]?? ucfirst(str_replace('_', ' ', $msg));
}

function obj2dict($array, $keys) {
  //return [$array['name'], $keys];
  if ( is_string( $keys ) ) {
    $keys = explode( ',', $keys );
  }
  $out = [];
  foreach($keys as $k) {
    if(array_key_exists ($k , $array)) {
      $out[$k] = $array[$k];
    } else {
      $out[$k] = $array->$k;
    }
  }
  return $out;
}

/**
 * @param mixed $list
 * @param mixed $keys
 *
 * @return [type]
 */
function list2array($list, $keys) {
  if ( is_string( $keys ) ) {
    $keys = explode( ',', $keys );
  }
  return array_map( function($i) use($keys) {
    return obj2dict($i,$keys);
  }, array_values($list) );
}

/**
 * Pon el subject en UTF8.
 *
 * @param mixed $subject
 *
 * @return [type]
 */
function subjectUtf8($subject) {
  return '=?UTF-8?B?'.base64_encode($subject).'?=';
}

/**
 * Previene inyección de script.
 *
 * @param mixed $subject
 *
 * @return [type]
 */
function hs($text) {
  return htmlspecialchars($text, ENT_COMPAT, 'UTF-8');
}
