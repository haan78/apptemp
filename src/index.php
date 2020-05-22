<?php
require_once "./lib/Web/Router.php";
require_once "./auth.php";
define("INCLUDEBYINDEX",TRUE);

class R extends \Web\Router {
    protected function log(\Web\RouterActionInfo $rai) {
        file_put_contents("log.txt", json_encode($rai).PHP_EOL,FILE_APPEND);
    }
    protected function doError(\Exception $ex) {
        $ex->getMessage();
    }

    protected function auth($action) : bool {        
        return Auth::control($action);
    }

    public function login() {
        include "login.php";
    }

    public function enter() {
        Auth::setSession();
    }

    public function main() {
        include "main.php";
    }

    public function ajax() {        
        include "ajax.php";
        $a = new A();
        $a->printAsJson();
        return $a->getLastOperationData();
    }

    public function exit() {
        Auth::killSession();
    }
}

new R( (isset($_GET["a"]) ? trim($_GET["a"]) : "main" ) );