<?php
/*
 * Configuración de las rutas de la aplicación.
 */
require 'routes-api-v1.php';
Router::addRoutes([
  'GET' => [
    "/" => "HomeController::index",
    "/about" => "HomeController::about",
    "/active-account/:token" => "SessionController::active_account",
    "/categories" => "CategoryController::index",
    "/company/:id/tasks/new" => 'CompanyController::tasks_new',
    "/company/:id" => "CompanyController::get",
    "/companies" => "CompanyController::index",
    "/contacts" => "ContactController::index",
    "/dashboard" => "DashboardController::index",
    "/farm/:id" => "FarmController::get",
    "/farms" => "FarmController::index",
    "/legal" => "HomeController::legal",
    "/login" => "HomeController::login",
    "/logout" => "HomeController::logout",
    "/parcel/:id" => "ParcelController::get",
    "/parcels" => "ParcelController::index",
    "/pdf/notebook" => "DashboardController::_pdf",
    "/plot/:id" => "PlotController::get",
    "/plots" => "PlotController::index",
    "/products" => "ProductController::index",
    "/profile" => "DashboardController::profile",
    "/recover" => "HomeController::recover",
    "/register" => "HomeController::register",
    "/resend-register" => "SessionController::resend_register",
    "/reset-password/:token" => "SessionController::reset_password",
    "/session-expired" => "HomeController::session_expired",
    "/uoms" => "UomController::index",
    "/users" => "UserController::index",
    "/task/:id" => "TaskController::get",
    "/tasks" => "TaskController::index",
  ],
  'POST' => [
    "/active-account" => "SessionController::verify_account",
    "/login" => "SessionController::login",
    "/profile" => "DashboardController::save_profile",
    "/recover" => "SessionController::recover",
    "/register" => "SessionController::register",
    "/set-new-password" => "SessionController::set_new_password",
  ],
]);
require 'routes-test.php';