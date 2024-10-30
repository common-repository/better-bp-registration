<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

trait Singleton {
    static private $instance = null;

    private function __construct() { /* ... @return Singleton */  }  // new Singleton
    private function __clone() { /* ... @return Singleton */  }  //е
    private function __wakeup() { /* ... @return Singleton */  }  // unserialize

    static public function getInstance() {
        return 
            self::$instance===null
            ? self::$instance = new static()//new self()
            : self::$instance;

    }
}
