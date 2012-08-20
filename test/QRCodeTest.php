<?php
class QRCodeTest extends PHPUnit_Framework_TestCase
{
    protected $qrcode;
    protected $grid;
    
    protected function setUp()
    {
        $this->qrcode = new QRCode();
        $this->grid = new Grid(4);
    }

    public function testGrid(){
    	$this->assertEquals(array("0000","0000","0000","0000"), $this->grid->exportToMatrix(), "Grid size");
    	$this->grid->setLine(1, 15);
    	$this->assertEquals(array("0000","1111","0000","0000"), $this->grid->exportToMatrix(), "Line push");
    	$this->grid->setCol(1, 15);
    	$this->assertEquals(array("0100","1111","0100","0100"), $this->grid->exportToMatrix(), "Col push");
    	$this->grid->setValue(3, 3, 1);
    	$this->assertEquals(array("0100","1111","0100","0101"), $this->grid->exportToMatrix(), "Value push");
    	$this->grid->invertCol(3);
    	$this->assertEquals(array("0101","1110","0101","0100"), $this->grid->exportToMatrix(), "Col Invert push");
    	$this->grid->invertLine(3);
    	$this->assertEquals(array("0101","1110","0101","1011"), $this->grid->exportToMatrix(), "Line Invert push");
    }
    
    public function testAnnexeG(){
    	$data = "01234567";
    	$version = 1;
    	$quality = "M";
    	
    	$data = $this->qrcode->encodeNumericData($data, $version);
		$data = $this->qrcode->generateCodewordsFromBitstream($data, $version, $quality);
		$arr = array(16,32,12,86,97,128,236,17,236,17,236,17,236,17,236,17);
		$this->assertEquals($arr, $data, "Codewords");
		
		$data = $this->qrcode->calculateErrorCorrection($data, $version, $quality);
		$arr = array(16,32,12,86,97,128,236,17,236,17,236,17,236,17,236,17,165,36,212,193,237,54,199,135,44,85);
		$this->assertEquals($arr, $data, "Error correction");
		
		$grid = $this->qrcode->generateGrid($data, $version, $quality);
		$matrix = array(
			"111111100001001111111", "100000100011001000001", "101110100100101011101", "101110100000101011101", "101110100111001011101",
            "100000100100001000001", "111111101010101111111", "000000000101000000000", "000000100000000000000", "100001011110000001000",
            "101100110001110111011", "100110000000100011000", "100011111101110110100", "000000001111011101000", "111111100010001000100",
            "100000100111011100001", "101110100100000001000", "101110100000000000100", "101110100111110110000", "100000100100100010010",
            "111111100011110110000"
        );
		$this->assertEquals($matrix, $grid->exportToMatrix());
    }
    
    public function testEncodingNumericA(){
    	$data = "01234567";
    	$version = 1;
    	
    	$this->assertEquals("000100000010000000001100010101100110000110000", $this->qrcode->encodeNumericData($data, $version), "Numeric Encoding");
    }
    
	public function testEncodingNumericB(){
    	$data = "0123456789012345";
    	$version = 1;
    	
    	$this->assertEquals("000100000100000000001100010101100110101001101110000101001110101001010000", $this->qrcode->encodeNumericData($data, $version), "Numeric Encoding");
    }
    
	public function testEncodingAlphaNumericA(){
    	$data = "AC-42";
    	$version = 1;
    	
    	$this->assertEquals("001000000010100111001110111001110010000100000", $this->qrcode->encodeAlphaNumericData($data, $version), "Alphanumeric Encoding");
    }
}
