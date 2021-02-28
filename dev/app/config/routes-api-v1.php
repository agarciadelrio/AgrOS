<?php
/*
 * Configuración de las rutas de la API.
 */
$r = '/api/v1';
Router::addRoutes([
  'GET' => [
    "$r/tasks/:id" => "TaskController::get_task",
    "$r/companies" => "CompanyController::load",
    "$r/companies/:id/farms" => "CompanyController::get_farms",
    "$r/farms" => "FarmController::load",
    "$r/parcels" => "ParcelController::load",
    "$r/plots" => "PlotController::load",
    "$r/products" => "ProductController::load",
    "$r/tasks" => "TaskController::load",
    "$r/users/:id/tasks" => "SessionController::get_tasks",
    "$r/uoms" => "UomController::load",
  ],
  'POST' => [
    "$r/category/update" => "CategoryController::update",

    "$r/companies/:id/delete" => "CompanyController::delete",
    "$r/companies/:id" => "CompanyController::update",
    "$r/companies" => "CompanyController::create",

    "$r/products/:id/delete" => "ProductController::delete",
    "$r/products/:id" => "ProductController::update",
    "$r/products" => "ProductController::create",

    "$r/uoms/:id/delete" => "UomController::delete",
    "$r/uoms/:id" => "UomController::update",
    "$r/uoms" => "UomController::create",
  ],
]);