<?php
class Auth {
    public static function control($action) {
        if ( $action == "main" ) {            
            if ( !self::checkSession() ) {                
                header("Location: index.php?a=login");
                return false;
            } else {                
                return true;
            }
        } elseif ($action == "ajax") {
            if ( !self::checkSession() ) {
                header('Content-Type: application/json;charset=utf-8;');
                echo json_encode(["status"=>false,"text"=>"Authentication has been failed"]);            
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    private static function checkSession() {
        if ( !isset($_SESSION) ) {
            session_start();
        }
        return isset($_SESSION["user"]);
    }

    public static function setSession() {
        if ( isset($_POST["email"]) ) {
            if ( !isset($_SESSION) ) {
                session_start();
            }
            $_SESSION["user"] = $_POST["email"];            
            header("Location: index.php?a=main");
        } elseif (isset($_GET["token"])) {
            //This is for JWT authentication
            header("Location: index.php?a=login");            
        } else {
            header("Location: index.php?a=login");            
        }
    }

    public static function killSession() {
        if ( !isset($_SESSION) ) {
            session_start();
        }
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
        }
        session_destroy();     
        header("Location: index.php?a=login");
    }
}