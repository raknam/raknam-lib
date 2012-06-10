<?php

require_once('../lib/luhnAlgorythm.php');

function checkSiret($cc, $debug = false) {
    $luhn  = luhn($cc);

    if ($debug) {
        echo $cc." : ".$luhn." - ".($luhn % 10 == 0 ? "valid" : "invalid");
    }

    return $luhn % 10 == 0;
}

checkSiret("1234567890123452", true);