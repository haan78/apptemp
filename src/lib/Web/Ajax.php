<?php

namespace Web {

    require_once __DIR__ . "/WebException.php";

    class AjaxResult {
        public $methodName;
        public $methodParams;
        public $methodResult;
        public $methodOutParams;
        public $methodDuration = 0;
        public ?\Exception $methodException = null;
    }

    abstract class Ajax {

        private AjaxResult $result;

        abstract protected function generateParam(string $name, string $command);

        public function __construct(bool $raiseonerror = true ,string $methodName = null, array $methodParams = null) {
            $this->result = new AjaxResult();
            $this->result->methodResult = null;
            $this->result->methodException = null;

            $time_start = microtime(true);
            try {
                if (is_null($methodName)) {
                    $this->getRequest($this->result->methodName, $this->result->methodParams, false);
                } elseif (is_null($methodParams)) {
                    $this->result->methodName = $methodName;
                    $this->getRequest($this->result->methodName, $this->result->methodParams, true);
                } else {
                    $this->result->methodName = $methodName;
                    $this->result->methodParams = $methodParams;
                }
                $this->runMethod($this->result->methodName, $this->result->methodParams, $this->result->methodResult, $this->result->methodOutParams);
            } catch (\Exception $ex) {
                $this->result->methodException = $ex;
            } finally {
                $this->result->methodDuration = microtime(true) - $time_start;
                if ( !is_null($this->result->methodException) && $raiseonerror ) {
                    throw $this->result->methodException;
                }
            }
            
        }

        public final function getResult() : AjaxResult {
            return $this->result;
        }

        private function renderStringParam(string $name, string $value) {
            if (preg_match("/^\~([_a-zA-Z0-9]+)$/", $value, $match)) {
                $command = strtolower($match[1]);
                if ($command == "null") {
                    return null;
                } else {
                    return $this->generateParam($name, $command);
                }
            } else {
                return $value;
            }
        }

        private function getRequest(&$name, &$params, bool $onlyParams = false) {
            $args = array();
            if ((isset($_SERVER["PATH_INFO"])) && (!is_null($_SERVER["PATH_INFO"]))) {
                $args = explode("/", $_SERVER["PATH_INFO"]);
                if (count($args) > 1) {
                    array_shift($args);
                }
            }

            /*if (!empty($_GET)) {
                $args = array_merge($args, $_GET);
            }*/

            if (!empty($_POST)) {
                $args = array_merge($args, $_POST);
            } else {
                $PD = file_get_contents("php://input");
                if (!empty($PD)) { //Json has been sent
                    $jd = json_decode($PD, true);
                    if (is_array($jd)) {
                        $args = array_merge($args, $jd);
                    }
                }
            }

            if (!$onlyParams) {
                if (isset($args["METHOD"])) {
                    $name = trim((string) $args["METHOD"]);
                    unset($args["METHOD"]);
                } elseif (isset($args[0])) {
                    $name = trim((string) $args[0]);
                    array_shift($args);
                } else {
                    throw new WebException( "Method is not declared", 1001);
                }
            }

            $params = $args;
        }

        private function getParamValue($params, $name, $ind, $defv) {
            if (isset($params[$name])) {
                $v = (is_string($params[$name]) ? $this->renderStringParam($name, $params[$name]) : $params[$name]);
            } elseif (isset($params[$ind])) {
                $v = (is_string($params[$ind]) ? $this->renderStringParam($name, $params[$ind]) : $params[$ind]);
            } else {
                $v = $defv;
            }
            return $v;
        }

        private function runMethod($method, $params, &$result, &$outs) {
            if (method_exists($this, $method)) {
                $rfm = new \ReflectionMethod($this, $method);
                if (($rfm->isPublic()) && (!$rfm->isConstructor()) && (!$rfm->isDestructor()) && (!$rfm->isStatic()) && (!$rfm->isFinal())) {
                    $refParams = $rfm->getParameters();
                    $pl = array();
                    $out_indexes = array();
                    for ($i = 0; $i < count($refParams); $i++) {
                        $pname = $refParams[$i]->getName();
                        $defv = ($refParams[$i]->isDefaultValueAvailable() ? $refParams[$i]->getDefaultValue() : null );
                        if (!$refParams[$i]->canBePassedByValue()) {
                            $pl[] = $this->getParamValue($params, $pname, $i, $defv);
                            $pl[$i] = &$pl[$i];
                            $out_indexes[] = $i;
                        } else {
                            $pl[] = $this->getParamValue($params, $pname, $i, $defv);
                        }
                    }

                    $result = $rfm->invokeArgs($this, $pl);
                    $outs = array();
                    //print_r($out_indexes); print_r($pl);
                    for ($i = 0; $i < count($out_indexes); $i++) {
                        $ind = $out_indexes[$i];
                        $refParams[$ind]->getName();
                        $outs[$refParams[$ind]->getName()] = $pl[$ind];
                    }
                } else {
                    throw new WebException( "Method $method is not accessible", 2002);
                }
            } else {
                throw new WebException( "Method $method not found", 2001);
            }
        }

    }

}