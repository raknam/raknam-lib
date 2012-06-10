<?php

require_once('../lib/RaknamValidator.class.php');

class RIOValidator extends RaknamValidator {
    
    private $oo;
    private $quality;
    private $contract;
    private $checksum;
    private $phone_number;
    
    private function _initData($rio) {
        $this->oo           = substr($rio, 0, 2);
        $this->quality      = substr($rio, 2, 1);
        $this->contract     = substr($rio, 3, 6);
        $this->checksum     = substr($rio, 9, 3);
        $this->phone_number = substr($rio, 12, 10);
    }
    
    private function _checkData() {
        if (!isset(self::$oolist[$this->oo]))              throw new Exception("Invalid OO");
        if (!in_array($this->quality, array('P','E'))) throw new Exception("Invalid Quality");
        foreach(str_split($this->checksum) as $c)
            if (strpos(self::CHARS, $c) === false)     throw new Exception("Invalid Checksum");
        if (substr($this->phone_number, 0, 1) != "0")  throw new Exception("Invalid Phone Number");
        if (!is_numeric($this->phone_number))          throw new Exception("Invalid Phone Number");
    }
    
    public function validate($rio) {
         $this->_initData($rio);

        try {
            $this->_checkData();
        } catch (Exception $e) {
            $this->lastException = $e;
            return false;
        }
        $res = $this->_checksumCalc() == (int)$this->checksum;
        if (!$res) $this->lastException = new Exception("Invalid Checksum");
        return $res;
    }
    
    private function _checksumCalc() {
        $str = "".$this->oo.$this->quality.$this->contract.$this->phone_number;
        $a = $b = $c = 0;
        foreach (str_split($str) as $char){
            $pos = strpos(self::CHARS, $char);
            $a = ($a + $pos) % 37;
            $b = (2 * $b + $pos) % 37;
            $c = (4 * $c + $pos) % 37;
        }
        $order = str_split(self::CHARS);
        return $order[$a].$order[$b].$order[$c];
    }
    
    public function toStringLastCheck($webres = false) {
        $res = sprintf("RIO: %s %s %s %s [%s]\n\n",
            $this->oo, $this->quality, $this->contract, $this->checksum, $this->phone_number);
        	
        $res.= "Opérator: ".$this->oo." ".self::$oolist[$this->oo]."\n";
        $res.= "Qualité: ".($this->quality == "P" ? "Particulier" : "Entreprise")."\n";
        $res.= "Numéro de contrat: ".$this->contract."\n\n";
        
        return $webres?nl2br($res):$res;
    }
    
    const CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+";
    public static $oolist = array (
        '01' => 'Orange', 
        '02' => 'SFR', 
        '03' => 'Bouygues Telecom / Universal Music Mobile / B&You', 
        '04' => 'Free', 
        '05' => 'Lycamobile', 
        '10' => 'Coriolis Télécom', 
        '21' => 'U-mobile', 
        '22' => 'Numericable / Estvideo ', 
        '23' => 'Simyo', 
        '48' => 'Ftmvno ', 
        '49' => 'Virgin Mobile / Breizh Mobile / Omea Telecom / Omer Telecom', 
    	'50' => 'Télé2', 
        '53' => 'Ten', 
        '54' => 'Carrefour', 
        '55' => 'Symacom ', 
        '56' => 'Transatel (Orange)', 
        '57' => 'NRJ (Orange)', 
        '60' => 'Futur Télécom', 
        '61' => 'La Poste Mobile / Simplicime / Simpleo / Débitel', 
        '62' => 'Mobisud', 
        '63' => 'Neuf Cegetel', 
        '64' => 'NRJ (SFR)', 
        '65' => 'Auchan', 
        '66' => 'Réglo Mobile / E. Leclerc Mobile', 
        '68' => 'Zéro-Forfait / Call In Europe', 
    	'69' => 'Prixtel', 
        '90' => 'France Télécom', 
        '91' => 'Sybase365', 
        '92' => 'Mblox', 
        '93' => 'Netsize', 
        '94' => 'Colt', 
        '95' => 'Ocito', 
        '96' => 'Verizon');
}

return;

$validator = new RIOValidator();
$validator->validate("04P1234566M00612345678");
echo $validator->toStringLastCheck(true);
$validator->validate("03P1234565590612345678");
echo $validator->toStringLastCheck(true);