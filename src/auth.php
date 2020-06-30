<?php
require_once "./lib/Web/Authorizer.php";
class Auth extends \Web\Authorizer {
    public function main() {
        session_start();
        if (!isset($_SESSION["user"])) {
            $this->redirect(["a"=>"login"]);  
        }             
    }
}