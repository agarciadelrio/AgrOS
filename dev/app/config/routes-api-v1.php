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
    "$r/contacts" => "ContactController::load",
    "$r/farms" => "FarmController::load",
    "$r/options/:collection" => "ApiController::options",
    "$r/parcels" => "ParcelController::load",
    "$r/plots" => "PlotController::load",
    "$r/products" => "ProductController::load",
    "$r/tasks" => "TaskController::load",
    "$r/users" => "UserController::load",
    "$r/users/:id/tasks" => "SessionController::get_tasks",
    "$r/uoms" => "UomController::load",
  ],
  'POST' => [
    "$r/category/update" => "CategoryController::update",

    "$r/companies/:id/delete" => "CompanyController::delete",
    "$r/companies/:id" => "CompanyController::update",
    "$r/companies" => "CompanyController::create",

    "$r/contacts/:id/delete" => "ContactController::delete",
    "$r/contacts/:id" => "ContactController::update",
    "$r/contacts" => "ContactController::create",

    "$r/farms/:id/delete" => "FarmController::delete",
    "$r/farms/:id" => "FarmController::update",
    "$r/farms" => "FarmController::create",

    "$r/parcels/:id/delete" => "ParcelController::delete",
    "$r/parcels/:id" => "ParcelController::update",
    "$r/parcels" => "ParcelController::create",

    "$r/plots/:id/delete" => "PlotController::delete",
    "$r/plots/:id" => "PlotController::update",
    "$r/plots" => "PlotController::create",

    "$r/products/:id/delete" => "ProductController::delete",
    "$r/products/:id" => "ProductController::update",
    "$r/products" => "ProductController::create",

    "$r/tasks/:id/delete" => "TaskController::delete",
    "$r/tasks/:id" => "TaskController::update",
    "$r/tasks" => "TaskController::create",

    "$r/uoms/:id/delete" => "UomController::delete",
    "$r/uoms/:id" => "UomController::update",
    "$r/uoms" => "UomController::create",

    "$r/users/:id/delete" => "UserController::delete",
    "$r/users/:id" => "UserController::update",
    "$r/users" => "UserController::create",
  ],
]);