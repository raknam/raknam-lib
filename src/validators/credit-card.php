<?php
class CreditCardValidator
{
    function check($cc, $debug = false) {
        $luhn  = Algorithm::luhn($cc);

        if ($debug) {
            echo $cc." : ".$luhn." - ".($luhn % 10 == 0 ? "valid" : "invalid");
        }

        return $luhn % 10 == 0;
    }
}
