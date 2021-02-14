<?php

/**
 * Controlador para la API version 1.
 */
class ApiV1Controller extends SessionController {

  /**
   * Busca el usuario si está autentificado.
   *
   * @return User
   */
  protected static function before_action() {
    self::authenticate();
    return R::load( 'user', $_SESSION['user']['id'] );
  }

  /**
   * Establece la propiedad por defecto del usuario.
   *
   * @param mixed $params=[]
   *
   * @return void
   */
  public static function set_default_property($params=[]) {
    $user = self::before_action();
    if($user->id == $params['user_id']) {
      $property_id = $params['property_id'];
      $property = $user
        ->withCondition(' id = ? ', [$property_id] )
        ->ownProperty;
      $property = $property[$property_id];
      if(!$property) {
        http_response_code(403);
        $data = [
          'msg' => _t('NOT MATCH PROPERTY ID'),
        ];
      } else {
        $data = [
          'msg' => _t('DEFAULT PROPERTY UPDATED'),
          'property_id' => $property->id,
        ];
        $user->property_id = $property->id;
        R::store($user);
      }
    } else {
      http_response_code(403);
      $data = [
        'msg' => _t('NOT MATCH USER ID'),
      ];
    }
    self::json($data);
  }

}