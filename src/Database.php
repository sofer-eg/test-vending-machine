<?php
namespace App;

class Database {

    use Singleton;

    public static $con = null;

    private function __construct() {

    }

    public static function init() {
        self::$con = new \mysqli(Config::get('db.host'), Config::get('db.user'), Config::get('db.pass'), Config::get('db.name'));
        if (self::$con->connect_error) {
            throw new \Exception('Error. Connecting to db (' . self::$con->connect_errno . ') ' . self::$con->connect_error);
        }
        self::$con->set_charset('utf8');
    }

    public function query($sql) {
        $res = self::$con->query($sql);
        if(self::$con->errno != 0) {
            throw new \Exception($sql . ' ' . self::$con->errno. ' ' . self::$con->error);
        }
        return $res;
    }

    public function execute($sql) {
        $this->query($sql);
        return startsWith($sql, 'INSERT') ? self::$con->insert_id : self::$con->affected_rows;
    }

    public function fetchAssoc(\mysqli_result $res) {
        return $res->fetch_assoc();
    }

    public function numRows(?\mysqli_result $res) {
        return is_null($res) ? 0 : $res->num_rows;
    }

    public function escape($value) {
        return self::$con->real_escape_string($value);
    }
}