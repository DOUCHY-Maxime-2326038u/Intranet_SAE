<?php

require 'Constants.php';

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

    public static function loadClass(string $S_className): bool
    {
        $directories = [
            Constants::ESSENTIALS_DIR,
            Constants::MODELS_DIR,
            Constants::VIEWS_DIR,
            Constants::CONTROLLERS_DIR
        ];

        foreach ($directories as $directory) {
            $S_fichier = Constants::rootDir() . $directory . "$S_className.php";
            if (self::_load($S_fichier)) {
                return true;
            }
        }

        return false;
    }
}

// Enregistrement de l'autoloader
spl_autoload_register('Autoloader::loadClass');
