<?php
/**
 * Created by PhpStorm.
 * User: raknam
 * Date: 16/07/2016
 * Time: 12:13
 */

namespace Raknam\Validators;

use Raknam\Lib\Validator;

class EAN13Validator extends Validator
{
    private $leftGroups;
    private $rightGroups;
    private $leftSets;
    private $prefix;

    static protected $setATable = [  //impaire
        "0001101" => 0, "0011001" => 1, "0010011" => 2, "0111101" => 3, "0100011" => 4,
        "0110001" => 5, "0101111" => 6, "0111011" => 7, "0110111" => 8, "0001011" => 9,
    ];
    static protected $setBTable = [  //pair
        "0100111" => 0, "0110011" => 1, "0011011" => 2, "0100001" => 3, "0011101" => 4,
        "0111001" => 5, "0000101" => 6, "0010001" => 7, "0001001" => 8, "0010111" => 9,
    ];
    static protected $setCTable = [  //droite
        "1110010" => 0, "1100110" => 1, "1101100" => 2, "1000010" => 3, "1011100" => 4,
        "1001110" => 5, "1010000" => 6, "1000100" => 7, "1001000" => 8, "1110100" => 9,
    ];
    static protected $prefixTable = [
        "AAAAAA" => 0, "AABABB" => 1, "AABBAB" => 2, "AABBBA" => 3, "ABAABB" => 4,
        "ABBAAB" => 5, "ABBBAA" => 6, "ABABAB" => 7, "ABABBA" => 8, "ABBABA" => 9,
    ];

    private function _checkAndInitData($data) {
        if (strlen($data) != 3+5+3+12*7)         throw new \Exception("Invalid length");

        foreach (str_split($data) as $c)
            if (!in_array($c, ["0","1"]))        throw new \Exception("Invalid bit found");

        if (substr($data, 0, 3) != "101")        throw new \Exception("Invalid start module bits");
        if (substr($data, 3+6*7, 5) != "01010")  throw new \Exception("Invalid middle module bits");
        if (substr($data, 3+12*7+5, 3) != "101") throw new \Exception("Invalid end module bits");

        $this->leftSets = "";
        $this->leftGroups = [];
        $this->rightGroups = [];

        //Left numbers
        $pos = 3;
        for ($i = 0; $i < 6; $i++) {
            $number = substr($data, $pos + 7*$i, 7);

            if (!isset(static::$setATable[$number]) && !isset(static::$setBTable[$number]))
                throw new \Exception("Invalid bits at number ".($i+1+6));

            if (!isset(static::$setATable[$number])) {
                $value = static::$setBTable[$number];
                $group = "B";
            } else {
                $value = static::$setATable[$number];
                $group = "A";
            }

            $this->leftSets .= $group;
            $this->leftGroups[] = $value;
        }
        if (!isset(static::$prefixTable[$this->leftSets]))
            throw new \Exception("Invalid left sets");
        $this->prefix = static::$prefixTable[$this->leftSets];

        //Right numbers
        $pos = 3 + 6*7 + 5;
        for ($i = 0; $i < 6; $i++) {
            $number = substr($data, $pos + 7*$i, 7);
            if (!isset(static::$setCTable[$number]))
                throw new \Exception("Invalid bits at number ".($i+1+6)." - Trying to find ".var_export($number, true)." in set C Table");

            $value = static::$setCTable[$number];

            $this->rightGroups[] = $value;
        }

        //Checking checksum
        $this->checksum = substr($this->getFullCode(), -1);
        $calculatedChecksum = $this->calculateCheckSum(substr($this->getFullCode(),0,-1));
        if ($this->checksum != $calculatedChecksum)
            throw new \Exception("Invalid checksum");
    }

    private function calculateCheckSum($numbers) {
        $sum = 0;
        for($i=0; $i<12; $i++) {
            $coef = ($i % 2 == 0) ? 1 : 3;
            $sum += $numbers[$i] * $coef;
        }
        return $sum % 10;
    }

    public function getFullCode() {
        return $this->prefix.implode($this->leftGroups).implode($this->rightGroups);
    }

    function validate($data)
    {
        try {
            $this->_checkAndInitData($data);
        } catch (Exception $e) {
            $this->lastException = $e;
            return false;
        }

        return true;
    }

}