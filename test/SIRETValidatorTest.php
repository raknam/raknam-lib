<?php
class SIRETCardValidatorTest extends PHPUnit_Framework_TestCase
{
    protected $validator;

    protected function setUp()
    {
        $this->validator = new SIRETValidator();
    }

    public function testCheck()
    {
        $this->assertTrue($this->validator->validate("73282932000074"));
    }
}
