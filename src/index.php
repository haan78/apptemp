<?php

require_once "./lib/Web/Router.php";

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

    public function main()
    {
        $this->javaScript("main.js",["selam", "oldu", "mu?"]);
    }
}

new R((isset($_GET["a"]) ? trim($_GET["a"]) : "main"));
