<?php
require_once "./lib/MySqlTool/MySqlToolCall.php";
class db {
    public static function connection(?string $schema = null) {
        $link = mysqli_init();
        mysqli_options($link, MYSQLI_OPT_CONNECT_TIMEOUT, 20);
        mysqli_real_connect($link);
        if ( !is_null($schema) ) {
            mysqli_select_db($link, $schema);
        }        
        mysqli_set_charset($link, "utf8");
        return $link;
    }

    public static function adapter(?string $schema = null) : \MySqlTool\MySqlToolCall {
        $link = self::connection($schema);
        $c = new \MySqlTool\MySqlToolCall($link);
        return $c;
    }
}