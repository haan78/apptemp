<?php

namespace Beluga {

    require_once "Tools.php";
    require_once "Document.php";
    require_once "Exception.php";

    class Db {
        use \Beluga\Tools;

        private $dataFolder;
        
        private $lastInsertId = null;
        private $count = 0;

        public function __construct($target)
        {
            if (!is_dir($target)) {
                mkdir($target);
            }
            
            $this->dataFolder = $target;
            $this->scope = new Scope($this);
        }

        public function getLastInsertId() {
            return $this->lastInsertId;
        }

        public function getCount() {
            return $this->count;
        }

        public function __setLastInsertId(string $id) {
            $this->lastInsertId = $id;
        }

        public function __setCount(int $count) {
            $this->count = $count;
        } 

        public function drop($name) : Db {
            if ( is_dir($this->dataFolder."/".$name) ) {
                $this->delete_directory($this->dataFolder."/".$name);
            }
            return $this;
        }

        public function document($name) : Document {
            if ( !is_dir($this->dataFolder."/".$name) ) {
                mkdir($this->dataFolder."/".$name);
            }
            return new \Beluga\Document($this->dataFolder."/".$name,$this);
        }

        public function list() : array {
            $list = scandir($this->dataFolder);
            $l = [];
            for ($i=0; $i<count($list); $i++) {
                $item = $list[$i];
                $d = $this->dataFolder."/".$item;
                if ($item !="." && $item != ".." && is_dir($d) ) {
                    array_push($l,$item);
                }
            }
            return $l;
        }
    }
}

