<?php
/*
 * Configuración los menús para usar con la funcion renderMenu.
 */
define('MENUS', [
  'main' => [
    'register' => ['/register'],
    'login' => ['/login'],
    'about' => ['/about'],
  ],
  'about' => [
    'home' => ['/'],
    'register' => ['/register'],
    'login' => ['/login'],
    'legal' => ['/legal'],
  ],
  'legal' => [
    'home' => ['/'],
    'register' => ['/register'],
    'login' => ['/login'],
    'about' => ['/about'],
  ],
  'back_home' => [
    '« Atrás' => ['javascript:history.back();'],
    'home' => ['/'],
  ],
  'home' => [
    'home' => ['/'],
  ],
  'login' => [
    'register' => ['/register'],
    'recover' => ['/recover'],
    'about' => ['/about'],
  ],
  'register' => [
    'login' => ['/login'],
    'recover' => ['/recover'],
    'about' => ['/about'],
  ],
  'recover' => [
    'register' => ['/register'],
    'login' => ['/login'],
    'about' => ['/about'],
  ],
  'app' => [
    'profile' =>['/profile'],
    'logout' =>['/logout'],
  ],
]);


?>