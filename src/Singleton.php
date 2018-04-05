<?php
namespace App;

trait Singleton {

    protected static $obj = null;

    public static function getInstance() {
        if(is_null(static::$obj)) {
            static::$obj = new static();
        }
        return static::$obj;
    }
}