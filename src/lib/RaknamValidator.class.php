<?php

namespace Raknam\Lib;

abstract class Validator {

    protected $lastException;
    
    public function getLastException() {
        return $this->lastException;
    }
    
    abstract function validate($data);
    
}