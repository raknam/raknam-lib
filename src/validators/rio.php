<?php
class RioValidator
{
    protected $operators;

    public function __construct( $operators )
    {
        foreach ($operators as $k => $v) $this->operators[$v] = $k;
    }

    protected function calcCCC($oo, $quality, $contract, $phone_number) {
        $str = "".$oo.$quality.$contract.$phone_number;
        $order = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+";
        $a = $b = $c = 0;
        foreach (str_split($str) as $char){
            $pos = strpos($order, $char);
            $a = ($a + $pos) % 37;
            $b = (2 * $b + $pos) % 37;
            $c = (4 * $c + $pos) % 37;
        }
        $order = str_split($order);
        return $order[$a].$order[$b].$order[$c];
    }

    public function check($rio, $phone_number, $debug = false) {

        $oo       = substr($rio, 0, 2);
        $quality  = substr($rio, 2, 1);
        $contract = substr($rio, 3, 6);
        $checksum = substr($rio, 9, 3);

        $res = $this->calcCCC($oo, $quality, $contract, $phone_number);

        if ($debug) {
            echo sprintf("RIO: %s %s %s %s [%s] : %s - %s<br /><br />",
            $oo, $quality, $contract, $checksum, $phone_number, $res, ($res == $checksum ? "valid" : "invalid"));

            echo "Opérator: ".$oo." ".$this->operators[$oo]."<br />";
            echo "Qualité: ".($quality == "P" ? "Particulier" : "Entreprise")."<br />";
            echo "Numéro de contrat: ".$contract."<br /><br />";
        }

        return $res == $checksum;
    }
}
