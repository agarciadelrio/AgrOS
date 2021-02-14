<?php

function class_loader( $name ) {
  $classes = [
    // MODELOS
    #'Attachable' => API_PATH . '/app/models/attachable.php',
    #'Handler' => API_PATH . '/app/models/handler.php',
    #'Model_User' => API_PATH . '/app/models/user.php',
    #'Plot' => API_PATH . '/app/models/plot.php',
    #'Property' => API_PATH . '/app/models/property.php',
    #'Unit' => API_PATH . '/app/models/unit.php',
    #'User' => API_PATH . '/app/models/user.php',
    #'Variety' => API_PATH . '/app/models/variety.php',
    // CONTROLADORES
    'AuthorizationsController' => API_PATH . '/app/controllers/AuthorizationsController.php',
    'ApiV1Controller' => API_PATH . '/app/api/v1/ApiV1Controller.php',
    'CompaniesController' => API_PATH . '/app/controllers/CompaniesController.php',
    'ContactsController' => API_PATH . '/app/controllers/ContactsController.php',
    'DashboardController' => API_PATH . '/app/controllers/DashboardController.php',
    'FarmsController' => API_PATH . '/app/controllers/FarmsController.php',
    'HandlersController' => API_PATH . '/app/controllers/HandlersController.php',
    'HomeController' => API_PATH . '/app/controllers/HomeController.php',
    'ItemsController' => API_PATH . '/app/controllers/ItemsController.php',
    'MembersController' => API_PATH . '/app/controllers/MembersController.php',
    'ParcelsController' => API_PATH . '/app/controllers/ParcelsController.php',
    'PermissionsController' => API_PATH . '/app/controllers/PermissionsController.php',
    'PlotsController' => API_PATH . '/app/controllers/PlotsController.php',
    'ProductsController' => API_PATH . '/app/controllers/ProductsController.php',
    'ProfilesController' => API_PATH . '/app/controllers/ProfilesController.php',
    'PropertiesController' => API_PATH . '/app/controllers/PropertiesController.php',
    'SessionController' => API_PATH . '/app/controllers/SessionController.php',
    'StuffsController' => API_PATH . '/app/controllers/StuffsController.php',
    'TasksController' => API_PATH . '/app/controllers/TasksController.php',
    'TaxonomiesController' => API_PATH . '/app/controllers/TaxonomiesController.php',
    'TeamsController' => API_PATH . '/app/controllers/TeamsController.php',
    'TestController' => API_PATH . '/app/controllers/TestController.php',
    'UsersController' => API_PATH . '/app/controllers/UsersController.php',
    'VarietiesController' => API_PATH . '/app/controllers/VarietiesController.php',
  ];
  if( isset($classes[$name]) ) {
    require_once $classes[$name];
  } else {
    $controller_file = dirname($_SERVER['SCRIPT_FILENAME']) . "/lib/controllers/$name.php";
    if(file_exists($controller_file)) {
      require_once $controller_file;
    }
  }
}

?>