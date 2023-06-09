<?php
/**
 * Macho_Store
 *
 * @package Macho
 * @since 1.0.5
 * @version 1.0.0
 */

/**
 * Macho_Store. Static class for fetching instances at any point.
 */
class Macho_Store extends Macho_Base{
    
    /**
     * @var string $version Class version.
     */
    public $version = '1.0.0';
    
    /**
     * @var array $instances all the stored instances
     */
    public static $instances = array();
    
    /**
      * Store an instance of anything to the $instances array.
      *
      * @since 1.0.0
      *
      * @return array
      *
      */
    public static function set($key = '', $object = null){
        static::$instances[$key] = $object;
    }

    /**
      * Retrieve an instance of anything from the $instances array.
      *
      * @since 1.0.0
      *
      * @return mixed. object or false
      *
      */
    public static function get($key = ''){
        if(isset(static::$instances[$key])){
          return static::$instances[$key];
        }
        return false;
    }

    /**
      * Forget an instance of anything from the $instances array.
      *
      * @since 1.0.0
      *
      * @return true
      *
      */
    public static function forget($key = ''){
        if(isset(static::$instances[$key])){
          unset(static::$instances[$key]);
        }
        return true;
    }
}