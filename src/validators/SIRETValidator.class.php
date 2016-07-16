<?php

namespace Raknam\Validators;

use Raknam\Lib\Algorithm;
use Raknam\Lib\Validator;

class SIRETValidator extends Validator {
    
    private $siret;

    private function _initData($siret) {
        $this->siret = $siret;
    }
    
    private function _checkData() {
        if (!is_numeric($this->siret)) throw new Exception('Invalid SIRET');
    }
    
    function validate($siret) {
        $this->_initData($siret);

        try {
            $this->_checkData();
        } catch (Exception $e) {
            $this->lastException = $e;
            return false;
        }
        $res = $this->_checksumCalc() % 10 == 0;
        if (!$res)
            $this->lastException = new Exception("Invalid Checksum");
        return $res;
    }
    
    private function _checksumCalc() {
        return Algorithm::Luhn($this->siret);
    }
    
    public function toStringLastCheck($webres = false) {
        $luhn = $this->_checksumCalc();
        $res = $this->siret." : ".$luhn." - ".($luhn % 10 == 0 ? "valid" : "invalid")."\n\n";
        $res.= "SIREN: ".substr($this->siret, 0, 3)." ".substr($this->siret, 3, 3)." ".substr($this->siret, 6, 3)."\n";
        $res.= "#Etablissement: ".substr($this->siret, 9, 4)."\n";
        $res.= "Checksum: ".substr($this->siret, -1);
        
        return $webres?nl2br($res):$res;
    }
}
