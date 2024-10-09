<?php

require 'essentials/Constants.php';

final class Autoloader
{
    
    private static function _load(string $S_toLoadFile): bool
    {
        if (is_readable($S_toLoadFile)) {
            require $S_toLoadFile;
            return true;
        }
        return false;
    }
    public static function classLoading(string $S_className, string $S_directory): bool
    {
        $S_fichier = Constants::rootDir() . $S_directory. "$S_className.php";
        return static::_load($S_fichier);
    }

    public static function essentialsLoading(string $S_className): bool
    {
        return self::classLoading($S_className, Constants::ESSENTIALS_DIR);
    }

    public static function modelsLoading(string $S_className): bool
    {
        return self::classLoading($S_className, Constants::MODELS_DIR);
    }

    public static function viewsLoading(string $S_className): bool
    {
        return self::classLoading($S_className, Constants::VIEWS_DIR);
    }

    public static function controllersLoading(string $S_className): bool
    {
        return self::classLoading($S_className, Constants::CONTROLLERS_DIR);
    }

    
}

// Enregistrement des autoloaders
spl_autoload_register('Autoloader::essentialsLoading');
spl_autoload_register('Autoloader::modelsLoading');
spl_autoload_register('Autoloader::viewsLoading');
spl_autoload_register('Autoloader::controllersLoading');

