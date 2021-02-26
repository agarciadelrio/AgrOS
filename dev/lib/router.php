<?php

set_error_handler(function($errno, $errstr, $errfile, $errline ){
  throw new Exception('<code>'.$errstr.'</code>' . $errfile . ":[$errline]");
});

/**
 * La clase Router es la encargada de procesar el URI para
 * determinar qué acción ejecutar.
 *
 * Es una clase final para que ninguna otra clase sobreescriba sus métodos.
 *
 * @package AgrOS
 * @subpackage Lib
 * @author  Antonio M. García del Río
 * @version 0.1
 */
final class Router {

  /**
   * Almacena las rutas.
   *
   * @var array
   */
  public static $ROUTES=[
    'GET' => [],
    'POST' => [],
  ];

  /**
   * Valor que almacena la ruta seleccionada.
   *
   * @var string
   */
  public static $route;

  public static function addRoutes($routes) {
    if(array_key_exists('GET', $routes)) {
      self::$ROUTES['GET'] = array_merge(self::$ROUTES['GET'], $routes['GET']);
    }
    if(array_key_exists('POST', $routes)) {
      self::$ROUTES['POST'] = array_merge(self::$ROUTES['POST'], $routes['POST']);
    }
  }

  /**
   * Ejecuta una ruta.
   *
   * En base al uri solicitado se localiza el patrón coincidente
   * en las rutas establecidas en el fichero de configuracoón de rutas
   * para ejecutar la acción indicada.
   *
   * @return string|void El contenido de la página o redirige a página 404
   */
  public static function run() {
    // Determinar si la peticion es GET o POST
    $request_method = $_SERVER['REQUEST_METHOD'];
    if( $request_method === 'POST' ) {
      $request_method = $_POST['_METHOD'] ?? 'POST';
    }
    // Analiza la cadena de la petición
    $url = parse_url( $_SERVER['REQUEST_URI'] );
    $url['path'] =  rtrim( $url['path'], '/' );
    if($url['path']=='') { $url['path']='/'; }
    parse_str( $url['query'] ?? NULL, $url['params'] );

    foreach( self::$ROUTES[$request_method] as $pattern => $route ) {
      $var_patt = '/:[a-z|_]*/';
      $pat = '/'.str_replace( '/', '\/', $pattern).'$/';
      $pat = preg_replace( $var_patt,'(.*)', $pat );
      if( preg_match( $pat, $url['path'], $match ) ) {
        preg_match_all( $var_patt, $pattern, $vars );
        $params = $url['params'];
        $ind = 1;
        foreach( $vars[0] as $var ) {
          $params[str_replace(':','',$var)] = $match[$ind++];
        }
        if( $request_method!='GET' ) {
          foreach( $_POST as $v => $k ) {
            $params[$v] = $k;
          }
        }
        Router::$route = $match[0];
        try {
          call_user_func($route, $params);
        } catch (\Throwable $th) {
          Controller::$layout = 'minimal';
          Controller::render('home/_error', ['message' => $th->getMessage()]);
        }
        return;
      }
    }

    header("HTTP/1.0 404 Not Found");
    Controller::$layout = 'minimal';
    Controller::render('home/_error', [
      'title' => '404 Not Found',
      'message' => '<h2 class="text-dark">' . $url['path'] . '</h2> not found'
    ]);
    //Controller::json([
    //  'code' => '404',
    //  'msg' => $url['path'] . ' not found',
    //]);
  }
}

?>