<?php
/*
 * Configuración de las rutas de la API.
 */
$r = '/api/v1';
Router::addRoutes([
  'GET' => [
    "$r/tasks/:id" => "TaskController::get_task",
    "$r/companies/:id/farms" => "CompanyController::get_farms",
    "$r/users/:id/tasks" => "SessionController::get_tasks",
    "$r/uoms" => "UomController::load",
  ],
  'POST' => [
    "$r/category/update" => "CategoryController::update",
    "$r/uoms/:id" => "UomController::update",
    "$r/uoms" => "UomController::create",
  ],
]);