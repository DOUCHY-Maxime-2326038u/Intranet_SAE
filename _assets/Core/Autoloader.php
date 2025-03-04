<?php

require 'Constants.php';

/**
 * Class Autoloader
 *
 * Classe finale pour charger automatiquement les classes utilisées dans l'application.
 */
final class Autoloader
{
    /**
     * Charge un fichier PHP si celui-ci est lisible.
     *
     * @param string $S_toLoadFile Chemin du fichier à charger.
     * @return bool True si le fichier a été chargé, sinon false.
     */
    private static function _load(string $S_toLoadFile): bool
    {
        if (is_readable($S_toLoadFile)) {
            require $S_toLoadFile;
            return true;
        }
        return false;
    }

    /**
     * Charge une classe en cherchant dans plusieurs répertoires.
     *
     * @param string $S_className Nom de la classe à charger.
     * @return bool True si la classe est chargée, sinon false.
     */
    public static function loadClass(string $S_className): bool
    {
        $ST_directories = [
            Constants::essentialsDir(),
            Constants::modelsDir(),
            Constants::viewsDir(),
            Constants::controllersDir(),
            Constants::utilsDir()
        ];

        foreach ($ST_directories as $directory) {
            $S_fichier = self::searchFile($directory, "$S_className.php");

            if ($S_fichier && self::_load($S_fichier)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Recherche récursivement un fichier dans un répertoire.
     *
     * @param string $S_dir Chemin du répertoire où chercher.
     * @param string $S_filename Nom du fichier à rechercher.
     * @return string|null Chemin complet du fichier s'il est trouvé, sinon null.
     */
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