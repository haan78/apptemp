<?php

namespace Web {
    interface RouterResponseType {
        public function outResult($result,?array $params ) : void;
        public function outException(\Exception $ex) : void;
    }

    class RouterResponseTypeDefaultHTML implements RouterResponseType {

        private function showObject($obj) {
            echo "<ul>";
            foreach($obj as $k => $v) {
                echo "<li>$k";
                if ( is_array($v) || is_object($v) ) {
                    $this->showObject($v);
                } else {
                    echo " : $v";
                }
                echo "</li>";
            }
            echo "</ul>";
        }

        public function outResult($result,?array $params ) : void {
            header("Content-Type: text/html; charset=utf-8");
            if ( is_array($result) || is_object($result) ) {
                $this->showObject($result);
            } else {
                echo $result;
            }
        }

        public function outException(\Exception $ex) : void {
            header("Content-Type: text/html; charset=utf-8");
            $this->showObjec([
                "ERROR"=>$ex->getMessage(),
                "CODE"=>$ex->getCode(),
                "FILE"=>$ex->getFile(),
                "LINE"=>$ex->getLine()
            ]);
        }
    }
    

    class RouterResponseTypeDefaultJSON implements RouterResponseType {

        public function outResult($result,?array $params ) : void {
            header("Content-Type: text/html; charset=utf-8");
            echo json_encode([
                "STATUS"=>"SUCCESS",
                "RESULT"=>$result
            ], JSON_PRETTY_PRINT);
        }

        public function outException(\Exception $ex) : void {
            header("Content-Type: text/html; charset=utf-8");
            echo json_encode([
                "STATUS"=>"ERROR",
                "MESSAGE"=>$ex->getMessage(),
                "CODE"=>$ex->getCode(),
                "FILE"=>$ex->getFile(),
                "LINE"=>$ex->getLine()
            ],JSON_PRETTY_PRINT);
        }
    }

    class RouterResponseTypeDefaultJS implements RouterResponseType {

        public $replaceCode = "VGhlcmUgaXMgbm8gZW1iZWRkZWQgZGF0YSBmcm9tIHRoZSBiYWNrZW5k";


        public function outResult($result,?array $params ) : void {
            header("Content-Type: application/json; charset=utf-8;");
            $js = file_get_contents($result);
            $json = json_encode($params);
            $js = str_replace($this->replaceCode,base64_encode($json),$js); 
            echo $js;
        }

        public function outException(\Exception $ex) : void {
            header("Content-Type: application/json; charset=utf-8;");
            echo "console.log(".json_encode([
                "STATUS"=>"ERROR",
                "MESSAGE"=>$ex->getMessage(),
                "CODE"=>$ex->getCode(),
                "FILE"=>$ex->getFile(),
                "LINE"=>$ex->getLine()
            ]).");";
        }
    }
}