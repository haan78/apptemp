<?php
namespace Web {
    class Authorizer {

        private $redirectUrl = null;

        protected final function abort(string $url) {
            $this->redirectUrl = $url;
        }

        public final function getRedirectUrl() {
            return $this->redirectUrl;
        }

        public final function check($action):bool {
            $this->aborted = false;
            if (\method_exists($this,$action)) {
                $rfm = new \ReflectionMethod($this, $action);
                if (($rfm->isPublic()) && (!$rfm->isConstructor()) && (!$rfm->isDestructor()) && (!$rfm->isStatic())) {
                    $rfm->invokeArgs($this, []);
                    return is_null($this->redirectUrl);
                } else {
                    throw new WebException(__METHOD__,"Authorizer method is not accessible",2001);
                }
            } else {
                return true;
            }
        }
    }
}