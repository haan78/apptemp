<?php

use Web\RouterResponse;
use Web\ResponseModelDefaultJS;
use Web\ResponseModelDefaultJSON;
use Web\ResponseModelDefaultHTML;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "./lib/Web/Router.php";
require_once "./lib/Web/ResponseModel.php";
require_once "./auth.php";

class R extends \Web\Router
{
    protected function log(\Web\RouterActionInfo $rai) {

    }

    public function main( RouterResponse $response ) : void {
        
        if ( auth::validate() ) {
            $response->model(new ResponseModelDefaultJS());
            $response->result("main.js",["user"=>"User","role"=>"ADMIN2"]);
        } else {        
            $response->redirect("_.php?a=login");    
        }       
    }

    public function ajax(RouterResponse $response) : void {
        $response->model(new ResponseModelDefaultJSON());
        include "ajax.php";        
        if ( auth::validate() ) {
            $a = new ajax();
            $response->result([ "RESULT"=>$a->getResult()->methodResult,"OUTS"=>$a->getResult()->methodOutParams ]);
            $response->log($a->getResult());
        } else {
            throw new \Web\WebException("Auth Faild");
        }        
    }

    public function login(RouterResponse $response) : void {
        $response->model(new ResponseModelDefaultJS());
        $response->result("login.js");
    }

    public function enter(RouterResponse $response) : void {
        if (auth::login()) {
            $response->redirect("/index.html");
        } else {
            $response->redirect("/index.html?m=w");
        }        
    }

    public function logout(RouterResponse $response) : void {
        auth::logout();
        $response->redirect('/index.html?m=e');
    }
}
$m=(isset($_GET["a"]) ? trim($_GET["a"]) :( !empty($_GET) ? array_keys($_GET)[0] : "main" ));

new R($m);
