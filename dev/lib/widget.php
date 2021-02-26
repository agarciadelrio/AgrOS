<?php

/**
 * [Description Widget]
 */
class Widget {
  /**
   * @var array
   */
  private static $methodsDict = [];

  /**
   * @param mixed $name
   * @param Closure $method
   *
   * @return [type]
   */
  public static function register($name, Closure $method) {
    self::$methodsDict[$name] = $method;
  }

  /**
   * @param mixed $name
   * @param mixed $arguments
   *
   * @return [type]
   */
  public static function __callstatic($name, $arguments){
    if(isset(self::$methodsDict[$name])) {
      return self::$methodsDict[$name]($arguments);
    } else {
      return "Widget::$name no está definido.";
    }
  }
}

/**
 * [Description W]
 */
class_alias('Widget','W');
