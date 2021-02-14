<?php
/**
 * Front de AgrOS
 *
 * @package AgrOS
 */

/** Establece la zona horaria */
date_default_timezone_set('Atlantic/Canary');

/** Carga el módulo de ORM RedBeanPHP */
require 'vendor/rb-mysql.php';
/**
 * Page-level DocBlock
 * @package AgrOS
 * @todo Tomar estos valores desde un fichero de configuración
 */
R::setup( 'mysql:host=127.0.0.1;dbname=agros', 'root', 'tolo' );

/** Define las constantes con nombre */
/**
 * Establece la URL raiz del sitio.
 *
 * @var string
 */
define('SITE_URL', getSiteUrl());
define('IMG_URL', SITE_URL . '/app/assets/img');
define('APP_NAME', 'AgrOS');
define('APP_VERSION', '1.0');
define('APP_ROOT', __DIR__);
define('APP_PATH', APP_ROOT . '/app');
define('VIEWS_PATH', APP_PATH . '/views');
define('LANG','es');
?>