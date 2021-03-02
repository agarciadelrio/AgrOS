<?php
/**
  * Este es el fichero de carga inicial, incluye todo el framework PHP
  * y los datos de configuración en un único lugar
  *
  * @package AgrOS
  */

define('DEBUG', TRUE);

require 'vendor/autoload.php';

define('API_PATH', __DIR__);

//- Clase Sessions
include API_PATH . '/lib/sessions.php';

//- Funciones para uso normalizado
include API_PATH . '/lib/functions.php';

//- Inicialización de variables
include API_PATH . '/lib/init.php';

//- Funciones helper para uso normalizado de componentes html
if(file_exists('app/helpers.php')) {
  include 'app/helpers.php';
}

//- Asociación de nombres de clase con sus ficheros de declaración
if(file_exists('app/config/class_loader.php')) {
  require 'app/config/class_loader.php';
}

//- Autocargador para Clases
spl_autoload_register('class_loader');

include API_PATH . '/lib/helpers.php';

//- Clase Vista
include API_PATH . '/lib/view.php';

//- Clase Modelo
include API_PATH . '/lib/model.php';

//- Clase Controlador
include API_PATH . '/lib/controller.php';

//- Clase Widget
include API_PATH . '/lib/widget.php';

//- Clase Enrutador
include API_PATH . '/lib/router.php';

//- Inicialización
//- Widgets
if(file_exists('app/widgets/calendar_widget.php')) {
  require 'app/widgets/calendar_widget.php';
}
if(file_exists('app/widgets/forms_widget.php')) {
  require 'app/widgets/forms_widget.php';
}
if(file_exists('app/widgets/menus_widget.php')) {
  require 'app/widgets/menus_widget.php';
}
if(file_exists('app/widgets/icons_widget.php')) {
  require 'app/widgets/icons_widget.php';
}

//- Datos de configuración del Site
if(file_exists('app/config/site.php')) {
  require 'app/config/site.php';
}

//- Configuración de la Base de Datos
if(file_exists('app/config/db.php')) {
  require 'app/config/db.php';
}

//- Definición de Rutas
if(file_exists('app/config/routes.php')) {
  require 'app/config/routes.php';
}

//- Definición de menús para las páginas estáticas del Site
if(file_exists('app/config/menus.php')) {
  require 'app/config/menus.php';
}

//- Ejecuta el parser de enrutado
Router::run();