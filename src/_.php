<?php
error_reporting(E_ALL);
require_once "./lib/Web/Router.php";
require_once "./auth.php";

class R extends \Web\Router
{
    protected function log($action, \Web\RouterActionInfo $rai) {

    }
    protected function doError($action, \Exception $ex)  {
        echo $ex->getMessage();
    }

    public function main() {
        if ( auth::validate() ) {
            $this->jsFile("main.js",["user"=>"User","role"=>"ADMIN2"]);
        } else {
            $this->redirect("_.php?a=login");
        }       
    }

    public function ajax() {
        include "ajax.php";        
        if ( auth::validate() ) {
            $a = new ajax();
            ajax::print($a->asArray());
            return $a->getLastOperationData();
        } else {
            ajax::print(["success"=>false,"text"=>"Auhtantication has faild"]);
            return null;
        }        
    }

    public function login() {
        $this->jsFile("login.js");
    }

    public function enter() {
        if (auth::login()) {
            $this->redirect("/index.html");
        } else {
            $this->redirect("/index.html?m=w");
        }
        
    }

    public function logout() {
        $this->redirect('/index.html?m=e');
    }
}

new R((isset($_GET["a"]) ? trim($_GET["a"]) : "main"));
