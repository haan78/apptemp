<?php
require_once "./lib/Web/Ajax.php";
require_once "./db.php";

class ajax extends \Web\Ajax {
    protected function generateParam(string $name, string $command) {
        return null;
    }
    
}