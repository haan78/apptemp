<?php
require_once "./lib/MySqlTool/MySqlToolCall.php";
define("DB_SCHEMA","test1");
class db {
    public static function connection() {
        $link = mysqli_init();
        mysqli_options($link, MYSQLI_OPT_CONNECT_TIMEOUT, 20);
        mysqli_real_connect($link);
        if ( !is_null(DB_SCHEMA) ) {
            mysqli_select_db($link, DB_SCHEMA);
        }        
        mysqli_set_charset($link, "utf8");
        return $link;
    }

    public static function adapter() : \MySqlTool\MySqlToolCall {
        $link = self::connection();
        $c = new \MySqlTool\MySqlToolCall($link);
        return $c;
    }
}