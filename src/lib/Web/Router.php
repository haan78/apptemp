<?php

namespace Web {
    
    require_once __DIR__ . "/WebException.php";
    require_once __DIR__ . "/Authorizer.php";
    require_once __DIR__ . "/RouterResponseType.php";

    class RouterActionInfo {
        public $name;
        public $time;
        public ?string $redirection = null;
        public $result;
        public ?\Exception $error;
        public int $duration;
        public $data = null;
    }

    class RouterResponse {
        private \Web\RouterResponseType $type;
        private $result;
        private ?array $params = null;
        private $logData = null;
        public $redirection_url = null;
        public function result($value,?array $params = null) : void {
            $this->result = $value;
            $this->params = $params;
        }

        public function __getResult() {
            return $this->result;
        }

        public function __getParams() :?array {
            return $this->params;
        }

        public function type(RouterResponseType $type):void {
            $this->type = $type;  
        }
        public function __getType() : RouterResponseType {
            return $this->type;
        }

        public function url(string $url) : void {
            $this->redirection_url = $url;
        } 

        public function __getUrl() : ?string {
            return $this->redirection_url;
        }

        public function __getLogData() {
            return $this->logData;
        }

        public function log($data) : void {
            $this->logData = $data;
        }
    }


    abstract class Router {

        public function __construct($action) {
            
            $time_start = microtime(true);
            $rai = new RouterActionInfo();
            $rai->name = $action;
            $rai->time = date("c");

            $response = new RouterResponse();

            try {
                if (method_exists($this, $action)) {
                    $rfm = new \ReflectionMethod($this, $action);
                    if (($rfm->isPublic()) && (!$rfm->isConstructor()) && (!$rfm->isDestructor()) && (!$rfm->isStatic()) ) {
                        $rfm->invokeArgs($this, [$response]);
                        $rai->data = $response->__getLogData();
                        if ( is_null($response->__getUrl()) ) {
                            $response->__getType()->outResult($response->__getResult(),$response->__getParams());
                        } else {
                            if ( !headers_sent($hf,$hl) ) {
                                $rai->redirection = $response->__getUrl();
                            } else {
                                throw new WebException("Header has been sent before $hf / $hl",1003);
                            }
                        }
                    } else {
                        throw new WebException("Router method is not accessible",1002);
                    }
                } else {
                    throw new WebException("Router method not found",1001);
                }
            } catch (\Exception $ex) {
                $rai->error = $ex;
                $response->__getType()->outException($ex);
            }

            $rai->duration = round(microtime(true) - $time_start, 5);
            $this->log($rai);
            if ( !is_null($response->__getUrl()) ) {
                header("Location: ".$response->__getUrl());
            }
        }

        abstract protected function log(\Web\RouterActionInfo $rai);

    }

}