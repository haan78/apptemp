<?php
namespace Web {
    
    class WebException extends \Exception {

        private $method;
        
        public function __construct($method, $message, $code = 0, \Exception $previous = null) {
            $this->method = $method;
            parent::__construct($message, $code, $previous);
        }
        
        public function __toString() {
            return __CLASS__ . ": [{$this->code}]: {$this->message} / $this->method";
        }
        
    }
}

