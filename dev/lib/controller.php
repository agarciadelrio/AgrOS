<?php
/**
 * Controller es la clase base para los controladores
 *
 * @package AgrOS
 * @subpackage Lib
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class Controller {
  /**
   * Plantilla base para renderizar la salida.
   *
   * @var string
   */
  static $layout='application';

  /**
   * @param string $view
   * @param mixed $params=[]
   *
   * @return string
   */
  public static function render($view, $params=[] ) {
    if(isset($_SESSION['redirect_params'])) {
      $params['from_redirect_params'] = $_SESSION['redirect_params'];
      unset($_SESSION['redirect_params']);
    }
    extract( $params );
    ob_start();
    require COMPONENTS_PATH . "/$view.php";
    View::body(ob_get_clean());
    require COMPONENTS_PATH . "/layouts/_" . static::$layout . ".php";
  }

  /**
   * @param string $container
   * @param string $view
   * @param mixed $params=null
   *
   * @return string
   */
  public static function partial($container, $view, $params=null) {
    if(is_array($params)) {
      extract( $params );
    }
    ob_start();
    require COMPONENTS_PATH . "/$view.php";
    View::$container(ob_get_clean());
    return View::$container();
  }

  /**
   * @param string $url
   * @param mixed $params=null
   *
   * @return void
   */
  public static function redirect( $url, $params=null ) {
    if($params!==null) {
      $_SESSION['redirect_params'] = $params;
    } else {
      if(isset($_SESSION['redirect_params'])) {
        unset($_SESSION['redirect_params']);
      }
    }
    header("Location: " . $url);
    die;
  }

  /**
   * Genera la salida de los datos ($data) en formato JSON.
   *
   * @param mixed $data
   * @param mixed $options=null
   *
   * @return void Cambia el Header de la respuesta del servidor a application/json.
   */
  public static function json($data, $options=null) {
    header('Content-Type: application/json');
    echo json_encode($data, JSON_NUMERIC_CHECK);
    die;
  }


  /**
   * @param string $to
   * @param string $subject
   * @param string $body
   *
   * @return bool
   */
  public static function mail($to, $subject, $body) {
    $from = 'agros@agros.jaira.com';
    $headers = "From: $from\r\n";
    $headers .= "Reply-To: $from\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    return mail($to, $subject, $body, $headers);
  }

  /******** MÉTODOS CRUD BASE ********/

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function load($params=[]) {
    $user = Sessions::authenticate();
    $items = array_values(R::find(static::$TABLE_NAME));
    self::json([
      'msg' => 'load',
      'items' => $items,
    ]);
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function create($params=[]) {
    $user = Sessions::authenticate();
    $json = file_get_contents('php://input');
    $item_data = json_decode($json);
    $item = R::dispense(static::$TABLE_NAME);
    $columns = static::$TABLE_COLUMNS;
    if (($key = array_search('id', $columns)) !== false) {
      unset($columns[$key]);
    }
    $item->import($item_data, $columns);
    $item->user = $user;
    R::store($item);
    self::json([
      'msg' => strtoupper(static::$TABLE_NAME) . ' CREADO',
      'item' => $item,
      'item_data' => $item_data,
    ]);
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function update($params=[]) {
    $user = Sessions::authenticate();
    $json = file_get_contents('php://input');
    $item_data = json_decode($json);
    $item = R::load(static::$TABLE_NAME, $params['id']);
    if($item) {
      $columns = static::$TABLE_COLUMNS;
      if (($key = array_search('id', $columns)) !== false) {
        unset($columns[$key]);
      }
      $item->import($item_data, $columns);
      $item->user = $user;
      R::store($item);
      self::json([
        'msg' => strtoupper(static::$TABLE_NAME) . ' ACTUALIZADO',
        'item' => $item,
        'item_data' => $item_data,
      ]);
    } else throw new Exception(strtoupper(static::$TABLE_NAME) . " {$params['id']} NO ENCONTRADO.");
  }

  /**
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function delete($params=[]) {
    $user = Sessions::authenticate();
    $json = file_get_contents('php://input');
    $item_data = json_decode($json);
    $item = R::load(static::$TABLE_NAME, $params['id']);
    if($item_data->id == $params['id'] &&  $item_data->action == 'delete') {
      R::trash( $item );
    }
    self::json([
      'msg' => strtoupper(static::$TABLE_NAME) . ' ELIMINADO',
      'item_data' => $item_data,
    ]);
  }

}

?>