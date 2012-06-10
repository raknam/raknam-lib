<?php

abstract class RaknamValidator {

    private $lastException;
    
    public function getLastException() {
        return $this->lastException;
    }
    
    abstract function validate($data);
    
}