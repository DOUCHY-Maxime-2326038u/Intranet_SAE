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
        $ST_directories = [
            Constants::essentialsDir(),
            Constants::modelsDir(),
            Constants::viewsDir(),
            Constants::controllersDir()
        ];

        foreach ($ST_directories as $directory) {
            $S_fichier = self::searchFile($directory, "$S_className.php");
            
            if ($S_fichier && self::_load($S_fichier)) {
                return true;
            }
        }

        return false;
    }

    public static function searchFile(string $S_dir, string $S_filename)
    {
        $S_dir = rtrim($S_dir, '/\\') . DIRECTORY_SEPARATOR;
        if (is_dir($S_dir)) {
            $DI_filelist = new DirectoryIterator($S_dir);
            foreach ($DI_filelist as $DI_file) {
                if ($DI_file->isDot()) {
                    continue;
                }

                if ($DI_file->isDir()) {
                    $S_res = self::searchFile($S_dir . $DI_file->getFilename(), $S_filename);
                    if ($S_res) {
                        return $S_res;
                    }
                } else {
                    if ($DI_file->getFilename() === $S_filename) {
                        return $S_dir . $DI_file->getFilename();
                    }
                }
            }
        }

        return null;
    }
}

// Enregistrement de l'autoloader
spl_autoload_register('Autoloader::loadClass');
