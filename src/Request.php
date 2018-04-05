<?php
namespace App;

class Request {

    use Singleton;

    private static $get;
    private static $post;
    private static $db;

    private function __construct() {}

    public static function init(array $get, array $post, Database $db) {
        self::$get = $get;
        self::$post = $post;
        self::$db = $db;
    }

    public static function get($key) {
        return self::escape(self::$get[$key] ?? null);
    }

    public static function post($key) {
        return self::escape(self::$post[$key] ?? null);
    }

    public static function escape($value) {
        if(is_null($value)) {
            return null;
        }

        return self::$db->escape($value);
    }

}