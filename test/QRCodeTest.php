<?php
class RioValidatorTest extends PHPUnit_Framework_TestCase
{
    protected $validator;

    protected function setUp()
    {
        $this->qrcode = new QRCode();
    }

    public function testAnnexeG(){
    	$data = "01234567";
    	$version = 1;
    	$quality = "M";
    	
    	$data = $this->qrcode->encodeNumericData($data, $version);
    	$this->assertTrue($data == "000100000010000000001100010101100110000110000000");
		$data = $this->qrcode->getCodewords($data, $version, $quality);
		$this->assertTrue(implode('', $data) == "00010000001000000000110001010110011000011000000011101100000100011110110000010001111011000001000111101100000100011110110000010001");
		//$data = $this->qrcode->calculateErrorCorrection($data, $version, $quality);
    }
    
    public function testEncodingA(){
    	$data = "01234567";
    	$version = 1;
    	
    	$this->assertTrue($this->qrcode->encodeNumericData($data, $version) == "000100000010000000001100010101100110000110000");
    }
    
	public function testEncodingB(){
    	$data = "0123456789012345";
    	$version = 1;
    	
    	$this->assertTrue($this->qrcode->encodeNumericData($data, $version) == "000100000100000000001100010101100110101001101110000101001110101001010000");
    }
    
	public function testEncodingC(){
    	$data = "AC-42";
    	$version = 1;
    	
    	$this->assertTrue($this->qrcode->encodeAlphaNumericData($data, $version) == "001000000010100111001110111001110010000100000");
    }
}
