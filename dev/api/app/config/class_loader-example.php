<?php

function class_loader( $name ) {
  $classes = [
    'DashboardController' => 'lib/controllers/DashboardController.php',
    'HomeController'      => 'lib/controllers/HomeController.php',
    'InfoController'      => 'lib/controllers/InfoController.php',
    'LoginController'     => 'lib/controllers/LoginController.php',
    'Model_User'          => 'lib/models/User.php',
    #'FarmController'      => 'lib/controllers/FarmController.php',
    #'ParcelController'     => 'lib/controllers/ParcelController.php',
  ];
  if( isset($classes[$name]) ) {
    require_once $classes[$name];
  } else {
    $controller_file = dirname($_SERVER['SCRIPT_FILENAME']) . "/lib/controllers/$name.php";
    #echo '<pre>' . $controller_file . '</pre>'; die;
    if(file_exists($controller_file)) {
      require_once $controller_file;
    }
  }
}