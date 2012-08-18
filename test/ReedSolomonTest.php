<?php
    
class ReedSolomonTest extends PHPUnit_Framework_TestCase {
    
    protected $rs;
    
    protected function setUp()
    {
        $this->rs = ReedSolomon::GetInstance();
    }
    
    public function testPolyGenerator(){
        $poly = $this->rs->rs_generator_poly(10);
        $this->assertEquals(array(1,216,194,159,111,199,94,95,113,157,193), $poly, "Poly Generation with 10 symbols");
    }
    
    public function testEncoding(){
        $msg_in = array(0x40, 0xd2, 0x75, 0x47, 0x76, 0x17, 0x32, 0x06, 0x27, 0x26, 0x96, 0xc6, 0xc6, 0x96, 0x70, 0xec);
        $msg_out = array(0x40, 0xd2, 0x75, 0x47, 0x76, 0x17, 0x32, 0x06, 0x27, 0x26, 0x96, 0xc6, 0xc6, 0x96, 0x70, 0xec,
            0xbc, 0x2a, 0x90, 0x13, 0x6b, 0xaf, 0xef, 0xfd, 0x4b, 0xe0);
            
        $val = $this->rs->rs_encode_msg($msg_in, 10);
        $this->assertEquals($msg_out, $val, "Encoding");
    }
    
}