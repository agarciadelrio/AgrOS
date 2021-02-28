<?php

/**
 * Carga dinámica de clases
 *
 * @param mixed $name
 *
 * @return [type]
 */
function class_loader( $name ) {
  $classes = [
    // MODELOS
    'Profile' => COMPONENTS_PATH . '/dashboard/profile',
    'Task' => COMPONENTS_PATH . '/task/task',
    // CONTROLADORES
    'ApiController' => COMPONENTS_PATH . '/api/api_controller',
    'CategoryController' => COMPONENTS_PATH . '/category/category_controller',
    'CompanyController' => COMPONENTS_PATH . '/company/company_controller',
    'ContactController' => COMPONENTS_PATH . '/contact/contact_controller',
    'DashboardController' => COMPONENTS_PATH . '/dashboard/dashboard_controller',
    'FarmController' => COMPONENTS_PATH . '/farm/farm_controller',
    'HomeController' => COMPONENTS_PATH . '/home/home_controller',
    'ParcelController' => COMPONENTS_PATH . '/parcel/parcel_controller',
    'PlotController' => COMPONENTS_PATH . '/plot/plot_controller',
    'ProductController' => COMPONENTS_PATH . '/product/product_controller',
    'SessionController' => COMPONENTS_PATH . '/session/session_controller',
    'TaskController' => COMPONENTS_PATH . '/task/task_controller',
    'UomController' => COMPONENTS_PATH . '/uom/uom_controller',
    'UserController' => COMPONENTS_PATH . '/user/user_controller',
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