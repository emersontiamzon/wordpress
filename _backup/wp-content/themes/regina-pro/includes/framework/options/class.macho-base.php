<?php
/**
 * Macho_Base
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */

/**
 * Macho_Base simple base class containing some useful functions
 */
class Macho_Base{
    
    /**
     * @var string $version Class version.
     */
    public $version = '1.0.0';
    
    /**
     * @var string $domain Class text domain.
     */
    public $domain = 'macho';
    
    /**
     * @var string $url macho url.
     */
    public static $url = null;
    
    /**
     * @var array $args Class args to be run attached after <code>parse_args</code>.
     */
    public $args = array();
    
    /**
      * Function used across extended classes used in conjunction with <code>parse_args</code> to format supplied arrays and ensure all keys are supplied.
      *
      * @since 1.0.0
      *
      * @return array
      *
      */
    private function default_args(){
        
        return array(
            
        );
        
    }
    
    /**
      * Recursive array merging from a default and supplied array.
      *
      * Very similar function to <code>wp_parse_args</code> except it filters through the whole array tree.
      *
      * @since 1.0.0
      *
      * @param array $a supplied value array.
      *
      * @param array $b default value array.
      *
      * @param string $filter optionally run the array through <code>apply_filters</code> before returning.
      *
      * @return array
      */
    protected static function parse_args( $a = array(), $b = array(), $filter = false ){
        
		$r = $b;

		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $r[ $k ] ) ) {
				if ( ! is_array( $r[ $k ] ) ) {
					$r[ $k ] = array();
				}
				$r[ $k ] = self::parse_args( $v, $r[ $k ] );
			} else {  
				$r[ $k ] = $v;
			}
		}
 
        if($filter){
            $r = apply_filters($filter, $r);   
        }
		return $r;   
    }
    
    /**
      * The provide function is just a shorthand wrapper for supplying a class object and method as array in WordPress actions and filters.
      *
      * @since 1.0.0
      *
      * @param string $key the method name to be added to the array: <code>array( &$this, $key )</code>.
      *
      * @return array
      */
    protected function provide($key = ''){
        return array(&$this, $key);
    }
    
    /**
      * Helper function used inside the <code>guid()</code> function to generate random strings.
      *
      * @since 1.0.0
      *
      * @param string $length the number of characters to return.
      *
      * @return string
      */
    protected function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
    
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
    
        return $key;
    }
    
    /**
      * Guid function used to reformat array index values before sending them to the fields.
      *
      * Simply replicates the behaviour found in framework javascript.
      *
      * @since 1.0.0
      *
      * @return string
      */
    protected function guid(){
        return 'i-' . $this->random_string(8) . '-' . $this->random_string(4) . '-' . $this->random_string(4) . '-' . $this->random_string(4) . '-' . $this->random_string(12);
    }
}