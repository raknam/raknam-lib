<?php
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
            	'raknam\lib\validator' => '/src/lib/Validator.class.php',
            	'raknam\validators\creditcardvalidator' => '/src/validators/CreditCardValidator.class.php',
                'raknam\validators\frenchsocialsecurityvalidator' => '/src/validators/FrenchSocialSecurityValidator.class.php',
                'raknam\lib\algorithm' => '/src/lib/Algorithm.class.php',
                'raknam\validators\siretvalidator' => '/src/validators/SIRETValidator.class.php',
                'raknam\validators\riovalidator' => '/src/validators/RIOValidator.class.php',
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
);
