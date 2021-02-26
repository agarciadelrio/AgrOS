<?php
/*
 * Configuración de las rutas de la aplicación.
 */
Router::addRoutes([
  'GET' => [
    "/" => "HomeController::index",
    "/about" => "HomeController::about",
    "/active-account/:token" => "SessionController::active_account",
    "/company/:id/tasks/new" => 'CompanyController::tasks_new',
    "/company/:id" => "CompanyController::get",
    "/dashboard" => "DashboardController::index",
    "/farm/:id" => "FarmController::get",
    "/legal" => "HomeController::legal",
    "/login" => "HomeController::login",
    "/logout" => "HomeController::logout",
    "/parcel/:id" => "ParcelController::get",
    "/plot/:id" => "PlotController::get",
    "/profile" => "DashboardController::profile",
    "/recover" => "HomeController::recover",
    "/register" => "HomeController::register",
    "/resend-register" => "SessionController::resend_register",
    "/reset-password/:token" => "SessionController::reset_password",
    "/session-expired" => "HomeController::session_expired",
    "/task/:id" => "TaskController::get",
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
require 'routes-api-v1.php';
require 'routes-test.php';