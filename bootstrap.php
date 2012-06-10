<?php
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'creditcardvalidator' => '/src/validators/credit-card.php',
                'frenchsocialsecurityvalidator' => '/src/validators/FrenchSocialSecurityValidator.class.php',
                'raknamvalidator' => '/src/lib/RaknamValidator.class.php',
                'algorithm' => '/src/lib/luhnAlgorithm.php',
                'siretvalidator' => '/src/validators/siret.php',
                'riovalidator' => '/src/validators/rio.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
);
