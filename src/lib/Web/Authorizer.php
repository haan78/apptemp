<?php
namespace Web {
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
}