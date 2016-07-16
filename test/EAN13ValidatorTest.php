<?php

/**
 * Created by PhpStorm.
 * User: raknam
 * Date: 16/07/2016
 * Time: 12:13
 */
class EAN13ValidatorTest extends PHPUnit_Framework_TestCase
{
    protected $validator;

    protected function setUp()
    {
        $this->validator = new \Raknam\Validators\EAN13Validator();
    }

    public function testCheck()
    {
        $this->assertTrue($this->validator->validate("10101011110110011001001101000010100011011100101010101000010001001001000111010011100101110010101"));
    }
}