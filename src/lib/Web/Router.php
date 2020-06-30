<?php

namespace Web {
    
    require_once __DIR__ . "/WebException.php";
    require_once __DIR__ . "/Authorizer.php";

    class RouterActionInfo {
        public $name;
        public $time;
        public bool $authentication;
        public ?string $redirection = null;
        public $result;
        public $errorDetails;
        public int $duration;
        public ?array $get;
        public ?array $post;
        public ?array $session;
        public ?array $client;
    }

    abstract class Router {

        private ?string $redirection = null;

        protected final function redirect(string $url) {
            $this->redirection = $url;
        }

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
                            $this->redirection = $auth->getRedirectUrl();
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
            $rai->redirection = $this->redirection;
            $this->log($action,$rai);
            if ( !is_null($rai->redirection) ) {
                header("Location: ".$this->redirection);
            }
        }

        public function jsFile($file,$data = null,$replaceCode = "VGhlcmUgaXMgbm8gZW1iZWRkZWQgZGF0YSBmcm9tIHRoZSBiYWNrZW5k") {
            $js = file_get_contents($file);
            $json = json_encode($data);
            $js = str_replace($replaceCode,base64_encode($json),$js);
            header('Content-Type: application/javascript');        
            echo $js;
        }

        abstract protected function log($action,\Web\RouterActionInfo $rai);
        abstract protected function doError($action,\Exception $ex);

    }

}