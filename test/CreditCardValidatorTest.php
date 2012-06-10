<?php
class CreditCardValidatorTest extends PHPUnit_Framework_TestCase
{
    protected $validator;

    protected function setUp()
    {
        $this->validator = new CreditCardValidator();
    }

    public function testCheck()
    {
        $this->assertTrue($this->validator->check("1234567890123452"));
    }
}
