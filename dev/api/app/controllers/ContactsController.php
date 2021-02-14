<?php

/**
 * Controlador para la gestión de Contacts.
 *
 * @package Controladores
 * @author Antonio M. García del Río
 * @version 0.1
 * @access public
 */
class ContactsController extends SessionController {

  /**
   * Devuelve el listado de contacts.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function index($params=[]) {
    self::check_api_token();
    $total_contacts = self::$USER->countOwn('contact');
    $contacts = R::getAll("SELECT id, name FROM contact WHERE user_id=?", [self::$USER->id]);

    self::json([
      'total_contacts' => $total_contacts,
      'contacts' => $contacts,
    ]);
  }

  /**
   * Devuelve los valores de un contact.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function get($params=[]) {
    self::check_api_token();
    $contact = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownContactList;
    if($contact) {
      $contact = reset($contact);
      $total_contacts = $contact ? 1:0;
      self::json([
        'total_contacts' => $total_contacts,
        'contact' => [
          'id' => $contact->id,
          'name' => $contact->name,
          'user_id' => $contact->user_id,
        ],
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO GET THIS RESOURCE',
        'total_contacts' => 0,
        'contact' => [],
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }

  /**
   * Crea o modifica un contact.
   *
   * @param mixed $params=[]
   *
   * @return json
   */
  public static function post($params=[]) {
    self::check_api_token();
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $id = intval($params['id']);
    if($id>0) {
      $mode = 'update';
      $contact = self::$USER->withCondition(' id = ? ', [$params['id']] )->ownContactList;
      $contact = reset($contact);
    } else {
      $mode = 'create';
      $contact = R::dispense('contact');
      $contact->user_id = self::$USER->id;
    }
    $total_contacts = $contact->id>0 ? 1 : 0;
    if($contact) {
      $contact->import( $data, 'name' );
      R::store( $contact );
      self::json([
        'total_contacts' => $total_contacts,
        'mode' => $mode,
        'contact' => [
          'id' => $contact->id,
          'name' => $contact->name,
        ],
        'data' => $data,
      ]);
    } else {
      header("HTTP/1.0 401 Unauthorized");
      self::json([
        'msg' => 'YOU ARE NOT AUTHORIZED TO UPDATE THIS RESOURCE',
        'mode' => $mode,
        'total_contacts' => 0,
        'contact' => [],
        'data' => $data,
        '_SESSION' => $_SESSION,
        '_SERVER' => $_SERVER,
      ]);
    }
  }
}