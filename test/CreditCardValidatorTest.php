<?php
class CreditCardValidatorTest extends PHPUnit_Framework_TestCase
{
    protected $validator;

    protected function setUp()
    {
        $this->validator = new \Raknam\Validators\CreditCardValidator();
    }

    public function testCheck()
    {
        $this->assertTrue($this->validator->validate("1234567890123452"));
    }
}
