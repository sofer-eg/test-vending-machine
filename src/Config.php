<?php
namespace App;

class Config {

    private static $config = [];

    private function __construct() {

    }

    public static function init($config) {
        self::$config = $config;
    }

    public static function get($name) {
        $name = explode('.', $name);
        $return = self::$config;
        foreach($name as $n) {
            if(isset($return[$n])) {
                $return = $return[$n];
            } else {
                throw new \Exception('Error. Configure key "'.$name.'" not exist!');
            }
        }
        return $return;
    }
}
