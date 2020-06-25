<?php

namespace Web {
    
    class WebRouterException extends \Exception {

        public function __construct($message, $code = 0, \Exception $previous = null) {            
            parent::__construct($message, $code, $previous);
        }

        public function __toString() {
            return __CLASS__ . ": [{$this->code}]: {$this->message}";
        }
    }

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

    abstract class Router {

        public function __construct($action) {
            $time_start = microtime(true);
            $rai = new RouterActionInfo();
            $rai->name = $action;
            $rai->time = date("c");
            try {
                if (method_exists($this, $action)) {
                    $rfm = new \ReflectionMethod($this, $action);
                    if (($rfm->isPublic()) && (!$rfm->isConstructor()) && (!$rfm->isDestructor()) && (!$rfm->isStatic())) {
                        if ( $this->auth($action) ) {
                            $rai->authentication = true;
                            $rai->result = $rfm->invokeArgs($this, []);
                        } else {
                            $rai->authentication = false;
                        }
                    } else {
                        throw new WebRouterException("Router method is not accessible",1002);
                    }
                } else {
                    throw new WebRouterException("Router method not found",1001);
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
            $fnc = "function GET_EMBEDED_DATA() { return $json; };";
            echo " <!DOCTYPE html><script>\n$js;\n$fnc</script>";
        }

        abstract protected function log($action,\Web\RouterActionInfo $rai);
        abstract protected function doError($action,\Exception $ex);
        abstract protected function auth($action) : bool;

    }

}