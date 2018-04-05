<?php
namespace App;

class Model {

    use Singleton;

    /** @var Database $db */
    private static $db = null;

    public static function init(Database $db) {
        self::$db = $db;
    }

    public function toSql(&$val, $key) {
        $val = "`" . $key . "` = '" . $val . "'";
    }

    public function toArray($sql, $key = '', $value = '', $flat = false) {
        $ret = array();
        $res = self::$db->query($sql);
        if(self::$db->numRows($res) > 0) {
            while ($data = self::$db->fetchAssoc($res)) {
                if ($key == '') {
                    if($value == '') {
                        $ret[] = $data;
                    } else {
                        $ret[] = $data[$value];
                    }
                } else {
                    if($value == '') {
                        $ret[$data[$key]] = $data;
                    } else {
                        $ret[$data[$key]] = $data[$value];
                    }
                }
            }
        }
        if($flat == true && isset($ret[0])) {
            $ret = $ret[0];
        }
        return $ret;
    }
}