<?php

// register class

spl_autoload_register(static function ($class) {
    if (strpos($class, 'Support') === 0) {
        $class = str_replace('Support', 'support', $class);
        $class = dirname(__DIR__) . DIRECTORY_SEPARATOR .  str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $class);

        include_once "$class.php";
    }
}, true, true);
