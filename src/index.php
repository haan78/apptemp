<?php

require_once "./lib/Web/Router.php";
require_once "./auth.php";


define("TITLE", "SUBUTAI FRAME WORK");

class R extends \Web\Router
{
    protected function log($action, \Web\RouterActionInfo $rai)
    {
    }
    protected function doError($action, \Exception $ex)
    {
        echo $ex->getMessage();
    }
    protected function auth($action): bool
    {
        return true;
    }

    public function atest() {
        echo "Yes";
    }

    public function main()
    {
        $this->JsDocument("main.js",null,"<img src='assets/loading.gif' style='display: block; margin-left: auto; margin-right: auto;'/>");
    }

    public function ajax() {

    }

    public function login() {

    }

    public function logout() {
        session_start();
        session_unset();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-42000, '/');
        }
        session_destroy();
        header('Location: index.phpa=login');
    }
}

new R((isset($_GET["a"]) ? trim($_GET["a"]) : "main"),new auth());
