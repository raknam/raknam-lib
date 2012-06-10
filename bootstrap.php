<?php
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
            	'raknamvalidator' => '/src/lib/RaknamValidator.class.php',    
            	'creditcardvalidator' => '/src/validators/credit-card.php',
                'frenchsocialsecurityvalidator' => '/src/validators/FrenchSocialSecurityValidator.class.php',
                'algorithm' => '/src/lib/Algorithm.class.php',
                'siretvalidator' => '/src/validators/siret.php',
                'riovalidator' => '/src/validators/RIOValidator.class.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
);
