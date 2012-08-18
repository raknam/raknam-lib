<?php
class QRCodeTest extends PHPUnit_Framework_TestCase
{
    protected $qrcode;
    
    protected function setUp()
    {
        $this->qrcode = new QRCode();
    }

    public function testAnnexeG(){
    	$data = "01234567";
    	$version = 1;
    	$quality = "M";
    	
    	$data = $this->qrcode->encodeNumericData($data, $version);
		$data = $this->qrcode->getCodewords($data, $version, $quality);
		$this->assertEquals("00010000001000000000110001010110011000011000000011101100000100011110110000010001111011000001000111101100000100011110110000010001", implode('', $data), "Codewords");
		$data = $this->qrcode->calculateErrorCorrection($data, $version, $quality);
		$this->assertEquals("0001000000100000000011000101011001100001100000001110110000010001111011000001000111101100000100011110110000010001111011000001000110100101001001001101010011000001111011010011011011000111100001110010110001010101", $data, "Error correction");
    }
    
    public function testEncodingA(){
    	$data = "01234567";
    	$version = 1;
    	
    	$this->assertEquals("000100000010000000001100010101100110000110000", $this->qrcode->encodeNumericData($data, $version), "Numeric Encoding");
    }
    
	public function testEncodingB(){
    	$data = "0123456789012345";
    	$version = 1;
    	
    	$this->assertEquals("000100000100000000001100010101100110101001101110000101001110101001010000", $this->qrcode->encodeNumericData($data, $version), "Numeric Encoding");
    }
    
	public function testEncodingC(){
    	$data = "AC-42";
    	$version = 1;
    	
    	$this->assertEquals("001000000010100111001110111001110010000100000", $this->qrcode->encodeAlphaNumericData($data, $version), "Alphanumeric Encoding");
    }
}
