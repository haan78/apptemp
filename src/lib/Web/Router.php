<?php

namespace Web {
    
    require_once __DIR__ . "/WebException.php";

    class RouterActionInfo {
        public $name;
        public $time;
        public $authentication;
        public $result;
        public $errorDetails;
        public $duration;
        public $get;
        public $post;
        public $session;
        public $client;
    }

    class Authorizer {
        public final function check($action) {
            if (\method_exists($this,$action)) {
                $rfm = new \ReflectionMethod($this, $action);
                if (($rfm->isPublic()) && (!$rfm->isConstructor()) && (!$rfm->isDestructor()) && (!$rfm->isStatic()) && $action != "check" ) {
                    if ( $rfm->invokeArgs($this, []) ) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    throw new WebException(__METHOD__,"Authorizer method is not accessible",2001);
                }
            } else {
                return true;
            }
        }
    }

    abstract class Router {

        public function __construct($action,Authorizer $auth = null) {
            $time_start = microtime(true);
            $rai = new RouterActionInfo();
            $rai->name = $action;
            $rai->time = date("c");
            try {
                if (method_exists($this, $action)) {
                    $rfm = new \ReflectionMethod($this, $action);
                    if (($rfm->isPublic()) && (!$rfm->isConstructor()) && (!$rfm->isDestructor()) && (!$rfm->isStatic())) {
                        if (  is_null($auth) || $auth->check($action) ) {
                            $rai->authentication = true;
                            $rai->result = $rfm->invokeArgs($this, []);
                        } else {
                            $rai->authentication = false;
                        }
                    } else {
                        throw new WebException(__METHOD__,"Router method is not accessible",1002);
                    }
                } else {
                    throw new WebException(__METHOD__,"Router method not found",1001);
                }
            } catch (\Exception $ex) {
                $rai->errorDetails = [
                    "code" => $ex->getCode(),
                    "message" => $ex->getMessage(),
                    "file" => $ex->getFile(),
                    "line" => $ex->getLine()
                ];
                $this->doError($action,$ex);
            }


            $rai->duration = round(microtime(true) - $time_start, 5);
            $rai->session = ( isset($_SESSION) ? $_SESSION : null );
            $rai->get = $_GET;
            $rai->post = $_POST;
            $rai->client = $_SERVER;
            $this->log($action,$rai);
        }

        public function javaScript($file,$data = null) {
            $js = file_get_contents($file);
            $json= json_encode($data);
            echo " <!DOCTYPE html><script>\n$js;\nfunction GET_EMBEDED_DATA() { return $json; }</script>";
        }

        abstract protected function log($action,\Web\RouterActionInfo $rai);
        abstract protected function doError($action,\Exception $ex);

    }

}