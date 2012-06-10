<?php
class CreditCardValidator
{
    function validate($cc, $debug = false) {
        $luhn  = Algorithm::Luhn($cc);

        if ($debug) {
            echo $cc." : ".$luhn." - ".($luhn % 10 == 0 ? "valid" : "invalid");
        }

        return $luhn % 10 == 0;
    }
}
