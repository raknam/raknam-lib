<?php

require_once('../lib/RaknamValidator.class.php');

class FrenchSocialSecurityValidator extends RaknamValidator {

    private $sex;
    private $year;
    private $month;
    private $dept;
    private $city;
    private $act;
    private $checksum;

    private $full;

    public function __contruct() {

    }

    private function checkdata() {
        if (!is_numeric($this->full)) 	                  return false;
        if (!in_array($this->sex, array(1,2)))            return false;
        if ($this->month > 12 && $this->month < 20)       return false;
        if ($this->month > 42 || $this->month == 0)       return false;
        if ($this->dept == 96)                            return false;
        if ($this->dept == 97 || $this->dept == 98) {
            $city = substr($this->$city, -2);
            if ($city == 0 || $city > 90)                 return false;
        } else {
            if ($this->city == 0 || $this->city > 990)    return false;
        }
        if ($this->act == 0)                              return false;
        if ($this->checksum == 0 || $this->checksum > 97) return false;
    }

    public function validate($numSS) {
        $this->full     = $numSS;
        $this->sex      = substr($numSS, 0, 1);
        $this->year     = substr($numSS, 1, 2);
        $this->month    = substr($numSS, 3, 2);
        $this->dept     = str_replace(array("A", "B"), array("1", "2"), substr($numSS, 5, 2));	//May be on 3 ciffers if $dept start with 97 or 98
        $this->city     = substr($numSS, 7, 3);
        $this->act      = substr($numSS, 10, 3);
        $this->checksum = substr($numSS, 13, 2);

        if (!$this->checkdata()) return false;
        return $this->checksumCalc();
    }

    private function checksumCalc() {
        return true;
    }

}


/*function checkFSS($numSS) {


$res = calcCCC($sex, $year, $month, $dept, $city, $act);

if ($debug) {
echo sprintf("Social Security Number: %s %s %s %s %s %s %s : %s - %s<br /><br />",
$oo, $quality, $contract, $checksum, $phone_number, $res, ($res == $checksum ? "valid" : "invalid"));

echo "Opérator: ".$oo." ".$invert_operators[$oo]."<br />";
echo "Qualité: ".($quality == "P" ? "Particulier" : "Entreprise")."<br />";
echo "Numéro de contrat: ".$contract."<br /><br />";
}

return $res == $checksum;
}*/

$validator = new FrenchSocialSecurityValidator();
var_dump($validator->validate("185073415211111"));