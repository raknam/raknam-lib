<?php
class RioValidatorTest extends PHPUnit_Framework_TestCase
{
    protected $validator;

    protected function setUp()
    {
        $this->validator = new RioValidator();
    }

    public function testCheckA()
    {
        $this->assertTrue($this->validator->validate("04P1234566M00612345678"));
    }

    public function testCheckB()
    {
        $this->assertTrue($this->validator->validate("03P1234565590612345678"));
    }
}
