<?php
/**
 * Ayudantes genéricos para generar HTML.
 *
 * @package AgrOS
 * @subpackage Helpers
 */

if(!function_exists('renderMenu')) {
  /**
   * Renderizar un menú.
   *
   * $items = [
   *
   *     $item => array = [
   *        0 => (string) URL.
   *        1 => (string) Estilo.
   *     ]]
   * @package AgrOS
   * @subpackage Helpers
   * @param array $items Lista de items del menú.
   * @param string $class='nav-link' Clase de estilos para cada item.
   *
   * @return string El HTML de los items del menú.
   */
  function renderMenu($items, $class='nav-link') {
    $out = '';
    $items = $items ?? [];
    foreach($items as $item => $link) {
      $klass = $link[1] ?? $class;
      $out .= "<a class=\"$klass\" href=\"{$link[0]}\">$item</a>";
    }
    return $out;
  }
}

?>