<?php

	//Temp test script

	require_once('QRCode.class.php');

		$data = "01234567";
    	$version = 1;
    	$quality = "M";
    	
    	$qrcode = new QRCode();
    	$data = $qrcode->encodeNumericData($data, $version);
		$data = $qrcode->generateCodewordsFromBitstream($data, $version, $quality);
		$data = $qrcode->calculateErrorCorrection($data, $version, $quality);
		$grid = $qrcode->generateGrid($data, $version, $quality);
		for ($i = 0; $i < count($data); $i++){
			$grid->setDataBlock($i, $data[$i]);
		}
		
		$grid->exportToHTML();
		
		var_dump($grid->exportToMatrix());