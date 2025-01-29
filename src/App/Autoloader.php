<?php

namespace App;
/**
 * Class Autoloader
 */
class Autoloader{

    /**
     * Enregistre notre autoloader
     */
    static function register(): void{
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Inclue le fichier correspondant à notre classe
     */
    static function autoload($fqcn): void{
        $path = str_replace('\\', '/', $fqcn);
        require ROOT . '/' . $path . '.php';
    }

}