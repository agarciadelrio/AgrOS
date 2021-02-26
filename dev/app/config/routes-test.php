<?php
/*
 * Configuración de las rutas pata hacer pruebas.
 */
$r = '/test';
Router::addRoutes([
  'GET' => [
    "$r/mail" => "TestController::_mail",
  ],
]);