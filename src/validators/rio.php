<?php

$operators = array(
	"Afone" => "66",
	"Auchan" => "65",
	"Bouygues Telecom / Universal Music Mobile / B&You" => "03",
	"Breizh Mobile" => "49",
	"Budget Telecom" => "68",
	"Carrefour" => "54",
	"Coriolis Télécom" => "10",
	"Coriolis Télécom Entreprise" => "01",
	"Free" => "04",
	"Futur Télécom" => "60",
	"Keyyo Mobile" => "68",
	"Lycamobile" => "05",
	"M6 Mobile (by Orange) " => "01",
	"Mobisud" => "62",
	"Neuf Cegetel" => "63",
	"NRJ (Orange)" => "57",
	"NRJ (SFR)" => "64",
	"Numericable / Estvideo " => "22",
	"Orange" => "01",
	"Ortel Mobile" => "23",
	"Prixtel" => "69",
	"Réglo Mobile / E. Leclerc Mobile" => "66",
	"La Poste Mobile / Simplicime / Simpleo / Débitel" => "61",
	"Sim+ " => "68",
	"Simyo" => "23",
	"Symacom " => "55",
	"SFR" => "02",
	"Télé2" => "50",
	"Ten" => "53",
	"Transatel (Bouygues)" => "21",
	"Transatel (Orange)" => "56",
	"U-mobile" => "21",
	"Virgin Mobile / Breizh Mobile / Omea Telecom / Omer Telecom" => "49",
	"Zéro-Forfait / Call In Europe" => "68",
	"Ftmvno " => "48",
	"France Télécom" => "90",
	"Sybase365 " => "91",
	"Mblox " => "92",
	"Netsize " => "93",
	"Colt " => "94",
	"Ocito " => "95",
	"Verizon " => "96",
);

foreach ($operators as $k => $v) $invert_operators[$v] = $k;

function calcCCC($oo, $quality, $contract, $phone_number) {
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

function checkRIO($rio, $phone_number, $debug = false) {
    global $invert_operators;

    $oo       = substr($rio, 0, 2);
    $quality  = substr($rio, 2, 1);
    $contract = substr($rio, 3, 6);
    $checksum = substr($rio, 9, 3);

    $res = calcCCC($oo, $quality, $contract, $phone_number);

    if ($debug) {
        echo sprintf("RIO: %s %s %s %s [%s] : %s - %s<br /><br />",
        $oo, $quality, $contract, $checksum, $phone_number, $res, ($res == $checksum ? "valid" : "invalid"));
        	
        echo "Opérator: ".$oo." ".$invert_operators[$oo]."<br />";
        echo "Qualité: ".($quality == "P" ? "Particulier" : "Entreprise")."<br />";
        echo "Numéro de contrat: ".$contract."<br /><br />";
    }

    return $res == $checksum;
}

checkRIO("04P1234566M0", "0612345678", true);
checkRIO("03P123456559", "0612345678", true);