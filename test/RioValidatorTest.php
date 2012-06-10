<?php
class RioValidatorTest extends PHPUnit_Framework_TestCase
{
    protected $validator;

    protected function setUp()
    {
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

        $this->validator = new RioValidator($operators);
    }

    public function testCheckA()
    {
        $this->assertTrue($this->validator->check("04P1234566M0", "0612345678"));
    }

    public function testCheckB()
    {
        $this->assertTrue($this->validator->check("03P123456559", "0612345678"));
    }
}
