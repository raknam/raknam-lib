<?php
	
	require_once('../lib/luth.php');
	
	function checkSiret($cc, $debug = false) {
		$luth  = luth($cc);
		
		if ($debug) {
			echo $cc." : ".$luth." - ".($luth % 10 == 0 ? "valid" : "invalid");
		}
		
		return $luth % 10 == 0;
	}
	
	checkSiret("1234567890123452", true);