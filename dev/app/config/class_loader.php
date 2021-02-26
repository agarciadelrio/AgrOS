<?php

function class_loader( $name ) {
  $classes = [
    // MODELOS
    'Profile' => COMPONENTS_PATH . '/dashboard/profile',
    'Task' => COMPONENTS_PATH . '/task/task',
    // CONTROLADORES
    'CompanyController' => COMPONENTS_PATH . '/company/company_controller',
    'DashboardController' => COMPONENTS_PATH . '/dashboard/dashboard_controller',
    'FarmController' => COMPONENTS_PATH . '/farm/farm_controller',
    'HomeController' => COMPONENTS_PATH . '/home/home_controller',
    'ParcelController' => COMPONENTS_PATH . '/parcel/parcel_controller',
    'PlotController' => COMPONENTS_PATH . '/plot/plot_controller',
    'SessionController' => COMPONENTS_PATH . '/session/session_controller',
    'TaskController' => COMPONENTS_PATH . '/task/task_controller',
    'TestController' => COMPONENTS_PATH . '/test/test_controller',
  ];
  if( isset($classes[$name]) ) {
    require_once $classes[$name] . '.php';
  } else {
    $controller_file = dirname($_SERVER['SCRIPT_FILENAME']) . "/lib/controllers/$name.php";
    if(file_exists($controller_file)) {
      require_once $controller_file;
    }
  }
}

?>