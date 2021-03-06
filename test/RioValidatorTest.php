<?php
class RioValidatorTest extends PHPUnit_Framework_TestCase
{
    protected $validator;

    protected function setUp()
    {
        $this->validator = new \Raknam\Validators\RIOValidator();
    }

    public function testCheckA() {
        $this->assertTrue($this->validator->validate("04P1234566M00612345678"));
    }

    public function testCheckB() {
        $this->assertTrue($this->validator->validate("03P1234565590612345678"));
    }
    
    public function testToStringLastCheck() {
        $this->validator->validate("03P1234565590612345678");
        $res = $this->validator->toStringLastCheck();
        $this->assertTrue(!empty($res), "testToStringLastCheck not empty");
    }
}
