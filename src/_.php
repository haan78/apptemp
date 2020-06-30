<?php

require_once "./lib/Web/Router.php";
require_once "./auth.php";

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
        $this->jsFile("main.js",["user"=>"User","role"=>"ADMIN2"]);
    }

    public function ajax() {

    }

    public function login() {
        $this->jsFile("login.js");
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
