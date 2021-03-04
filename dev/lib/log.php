<?php

Class Log {
  public static function save($type, $route, $msg) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $time = date('Y-m-d H:i:s');
    $data = "$time\t$ip\t$type\t$route\t$msg\n";
    file_put_contents( API_PATH . '/log/agros.log', $data, FILE_APPEND);
  }
}