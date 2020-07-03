<?php

namespace Web {
    
    require_once __DIR__ . "/WebException.php";
    require_once __DIR__ . "/Authorizer.php";

    class RouterActionInfo {
        public $name;
        public $time;
        public ?string $redirection = null;
        public $result;
        public ?string $error;
        public int $duration;
    }

    abstract class Router {

        private ?string $redirection;

        protected final function redirect(string $url) {
            $this->redirection = $url;
        }

        public function __construct($action) {
            $time_start = microtime(true);
            $rai = new RouterActionInfo();
            $rai->name = $action;
            $rai->time = date("c");
            $this->redirection = null;
            try {
                if (method_exists($this, $action)) {
                    $rfm = new \ReflectionMethod($this, $action);
                    if (($rfm->isPublic()) && (!$rfm->isConstructor()) && (!$rfm->isDestructor()) && (!$rfm->isStatic())) {
                        $rai->result = $rfm->invokeArgs($this, []);
                        if ( !is_null($this->redirection) ) {
                            if ( !headers_sent($hf,$hl) ) {
                                $rai->redirection = $this->redirection;
                            } else {
                                throw new WebException(__METHOD__,"Header has been sent before $hf / $hl",1003);
                            }
                        }
                    } else {
                        throw new WebException(__METHOD__,"Router method is not accessible",1002);
                    }
                } else {
                    throw new WebException(__METHOD__,"Router method not found",1001);
                }
            } catch (\Exception $ex) {
                $rai->error = $ex->getMessage();
                $this->doError($action,$ex);
            }

            $rai->duration = round(microtime(true) - $time_start, 5);
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