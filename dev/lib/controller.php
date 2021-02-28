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
   * Límite por defecto para los listados.
   *
   * @var string
   */
  static $LIMIT=20;

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
   * Carga los datos del modelo y sus relaciones _id.
   *
   * @param mixed $params=[]
   *
   * @return [type]
   */
  public static function load($params=[]) {
    // DEVEL: PARA CREAR CAMPOS AL VUELO
    //$item = R::load('task',1);
    //$link = R::load('contact',1);
    //$item->contact = $link;
    //R::store($item);
    //self::json([$item,$link]);
    //die;
    $user = Sessions::authenticate();
    $limit = ($params['limit'] ?? static::$LIMIT) or 1;
    $order = $params['order'] ?? '';
    $order = str_replace(':', ' ', $order);
    $order = str_replace('1', 'ASC', $order);
    $order = str_replace('2', 'DESC', $order);
    if($order) $order = " ORDER BY $order ";
    $page = $params['page'] ?? 1;
    $page -=1;
    if($page<0) $page=0;
    $total = R::count(static::$TABLE_NAME);
    $total_pages = (int)($total / $limit);
    if($page>$total_pages) $page = $total_pages;
    $offset = $page * $limit;
    $items = R::findAll(static::$TABLE_NAME, " $order LIMIT $limit OFFSET $offset");
    $joins = [];
    // comprobar si hay campos de relacion *_id
    foreach(static::$TABLE_COLUMNS as $c) {
      if(strpos($c,'_id')) {
        list($a) = explode('_id', $c);
        $joins[]=$a;
      }
    }
    // si ha encontrado campos de relación extrae la info con LEFT JOIN
    if($joins) {
      $table_name = static::$TABLE_NAME;
      $tables = implode(', ', $joins);
      $join_lines = [];
      $table_fields = implode(', ', array_map(function($t) use($table_name, &$join_lines) {
        $join_lines []= "LEFT JOIN $t ON $table_name.{$t}_id = $t.id";
        return "$t.*";
      }, $joins));
      $join_lines = implode(' ', $join_lines);
      $stuff = R::findMulti($tables, "SELECT $table_fields FROM $table_name $join_lines
        LIMIT $limit OFFSET $offset");
    } else {
      $stuff = NULL;
    }
    self::json([
      'msg' => 'load ' . static::$TABLE_NAME,
      'total'=> $total,
      'total_pages'=> $total_pages,
      'items' => array_values($items),
      'stuff' => $stuff,
      //'joins' => $joins,
      //'join_lines' => $join_lines,
    ]);
  }

  /**
   * Crea un registro en la BBDD.
   *
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
   * Actualiza los datos de un registro en la BBDD.
   *
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
   * Elimina un registro de la BBDD.
   *
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