<?php
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
            	'raknamvalidator' => '/src/lib/RaknamValidator.class.php',    
            	'creditcardvalidator' => '/src/validators/CreditCardValidator.class.php',
                'frenchsocialsecurityvalidator' => '/src/validators/FrenchSocialSecurityValidator.class.php',
                'algorithm' => '/src/lib/Algorithm.class.php',
                'siretvalidator' => '/src/validators/SIRETValidator.class.php',
                'riovalidator' => '/src/validators/RIOValidator.class.php',
                'qrcode' => '/src/lib/QRCode.php',
                'reedsolomon' => '/src/lib/ReedSolomon.class.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
);
