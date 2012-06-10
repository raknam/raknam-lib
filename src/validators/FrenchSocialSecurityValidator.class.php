<?php
class FrenchSocialSecurityValidator extends RaknamValidator {

    private $sex;
    private $year;
    private $month;
    private $dept;
    private $city;
    private $act;
    private $checksum;

    private $full;

    /**
     * Split data from full SS id
     * @param string $numSS must be 15 ciffers
     */
    private function _initData($numSS) {
        $this->full     = $numSS;
        
        $this->sex      = substr($numSS, 0, 1);
        $this->year     = substr($numSS, 1, 2);
        $this->month    = substr($numSS, 3, 2);
        $this->dept     = str_replace(array("A", "B"), array("1", "2"), substr($numSS, 5, 2));	//May be on 3 ciffers if $dept start with 97 or 98
        $this->city     = substr($numSS, 7, 3);
        $this->act      = substr($numSS, 10, 3);
        $this->checksum = substr($numSS, 13, 2);
    }
    
    /**
     * Check given data according tohttp://fr.wikipedia.org/wiki/Numéro_de_sécurité_sociale#Signification_des_chiffres_du_NIR
     * @throws Exception throw a exception if a rule is broken
     * @see RaknamValidator::checkdata()
     */
    private function _checkData() {
        if (!is_numeric($this->full))                     throw new Exception("Not numeric (full)");
        if (!in_array($this->sex, array(1,2)))            throw new Exception("Invalid Sex");
        if ($this->month > 12 && $this->month < 20)       throw new Exception("Invalid Month");
        if ($this->month > 42 || $this->month == 0)       throw new Exception("Invalid Month");
        if ($this->dept == 96)                            throw new Exception("Invalid Dept");
        if ($this->dept == 97 || $this->dept == 98) {
            $city = substr($this->$city, -2);
            if ($city == 0 || $city > 90)                 throw new Exception("Invalid City");
        } else {
            if ($this->city == 0 || $this->city > 990)    throw new Exception("Invalid City");
        }
        if ($this->act == 0)                              throw new Exception("Invalid Birth Order Act Number");
        if ($this->checksum == 0 || $this->checksum > 97) throw new Exception("Invalid Checksum");
    }

    /**
     * Do the validation
     * @return bool true if given data is valid
     * @see RaknamValidator::validate()
     */
    public function validate($numSS) {
        $this->_initData($numSS);

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
        $num = $this->sex.$this->year.$this->month.$this->dept.$this->city.$this->act;
        $num = 97 - ($num - (floor($num / 97) * 97));
        return $num;
    }
    
    /**
     * Return a debug stack on last validation
     * @param bool $webres if true, return <br/> instead \n
     * @return string return debug stack
     */
    public function toStringLastCheck($webres = false) {
        $res = "Numéro de Sécurité Sociale Français\n\n";
        $res.= $this->full."\n\n";
        $res.= sprintf("Sexe: %d - %s\n", $this->sex, $this->sex==1?"Homme":"Femme");
        $res.= sprintf("Mois/Année de naissance: %02d/%02d\n", $this->month, $this->year);
        $res.= sprintf("Département/Commune: %05d\n", $this->dept.$this->city);
        $res.= sprintf("Numéro d'ordre de l'acte de naissance: %03d\n", $this->act);
        $res.= sprintf("Checksum: %02d\n", $this->checksum);
        
        return $webres?nl2br($res):$res;
    }

}

return;

$validator = new FrenchSocialSecurityValidator();
$validator->validate("185073411111174");
echo $validator->toStringLastCheck(true);
