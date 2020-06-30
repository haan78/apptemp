<?php
namespace Web {
    class Authorizer {

        private $aborted = false;

        public final function isAborted():bool {
            return $this->aborted;
        }

        protected final function redirect(array $params = []) {
            $uri = $_SERVER["SCRIPT_NAME"]."?".http_build_query($params);
            $this->aborted = true;
            //die("Location: $uri");
            header("Location: $uri");
        }

        public final function check($action):bool {
            $this->aborted = false;
            if (\method_exists($this,$action)) {
                $rfm = new \ReflectionMethod($this, $action);
                if (($rfm->isPublic()) && (!$rfm->isConstructor()) && (!$rfm->isDestructor()) && (!$rfm->isStatic())) {
                    $rfm->invokeArgs($this, []);
                    return !$this->aborted;
                } else {
                    throw new WebException(__METHOD__,"Authorizer method is not accessible",2001);
                }
            } else {
                return true;
            }
        }
    }
}