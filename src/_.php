<?php

use Web\RouterResponse;
use Web\RouterResponseTypeDefaultJS;
use Web\RouterResponseTypeDefaultJSON;
use Web\RouterResponseTypeDefaultHTML;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "./lib/Web/Router.php";
require_once "./lib/Web/RouterResponseType.php";
require_once "./auth.php";

class R extends \Web\Router
{
    protected function log(\Web\RouterActionInfo $rai) {

    }

    public function main( RouterResponse $response ) : void {
        
        if ( auth::validate() ) {
            $response->type(new RouterResponseTypeDefaultJS());
            $response->result("main.js",["user"=>"User","role"=>"ADMIN2"]);
        } else {        
            $response->url("_.php?a=login");    
        }       
    }

    public function ajax(RouterResponse $response) : void {
        $response->type(new RouterResponseTypeDefaultJSON());
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
        $response->type(new RouterResponseTypeDefaultJS());
        $response->result("login.js");
    }

    public function enter(RouterResponse $response) : void {
        if (auth::login()) {
            $response->url("/index.html");
        } else {
            $response->url("/index.html?m=w");
        }        
    }

    public function logout(RouterResponse $response) : void {
        $response->type(new RouterResponseTypeDefaultHTML());
        auth::logout();
        $response->url('/index.html?m=e');
    }
}

new R((isset($_GET["a"]) ? trim($_GET["a"]) : "main"));
