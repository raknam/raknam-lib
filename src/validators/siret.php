<?php

require_once('../lib/luhnAlgorythm.php');

function checkSiret($siret, $debug = false) {
	$luhn  = luhn($siret);

	if ($debug) {
		echo $siret." : ".$luhn." - ".($luhn % 10 == 0 ? "valid" : "invalid")."<br /><br />";
			
		echo "SIREN: ".substr($siret, 0, 3)." ".substr($siret, 3, 3)." ".substr($siret, 6, 3)."<br />";
		echo "#Etablissement: ".substr($siret, 9, 4)."<br />";
		echo "Checksum: ".substr($siret, -1);
	}

	return $luhn % 10 == 0;
}

checkSiret("73282932000074", true);
