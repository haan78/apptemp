<?php

class auth {
    public static function validate() : bool {
        if (!isset($_SESSION)) {
            session_start();
        }
        return isset($_SESSION["user"]);
    }

    public static function login() : bool {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (isset($_POST["user"])) {
            $_SESSION["user"] = trim($_POST["user"]);
            return true;
        } else {
            return false;
        }
    }

    public static function logout() : void {
        if (!isset($_SESSION)) {
            session_start();
        }
        session_unset();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-42000, '/');
        }
        session_destroy();
    }
}