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

function _t($msg) {
  return $msg;
}

?>