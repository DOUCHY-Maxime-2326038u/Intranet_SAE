<?php

/**
 * Class Constants
 *
 * Fournit les constantes et méthodes utilitaires pour accéder aux répertoires de l'application.
 */
final class Constants
{
    const VIEWS_DIR = '/modules/Views/';
    const MODELS_DIR = '/modules/Models/';
    const CONTROLLERS_DIR = '/modules/Controllers/';
    const ESSENTIALS_DIR = '/_assets/Core/';
    const UTILS_DIR = '/_assets/Utils/';

    /**
     * Retourne le répertoire racine de l'application.
     *
     * @return string Chemin absolu du répertoire racine.
     */
    public static function rootDir(): string
    {
        return realpath(__DIR__ . '/../../');
    }

    /**
     * Retourne le répertoire des éléments essentiels.
     *
     * @return string Chemin du répertoire des essentiels.
     */
    public static function essentialsDir(): string
    {
        return self::rootDir() . self::ESSENTIALS_DIR;
    }

    /**
     * Retourne le répertoire des vues.
     *
     * @return string Chemin du répertoire des vues.
     */
    public static function viewsDir(): string
    {
        return self::rootDir() . self::VIEWS_DIR;
    }

    /**
     * Retourne le répertoire des modèles.
     *
     * @return string Chemin du répertoire des modèles.
     */
    public static function modelsDir(): string
    {
        return self::rootDir() . self::MODELS_DIR;
    }

    /**
     * Retourne le répertoire des contrôleurs.
     *
     * @return string Chemin du répertoire des contrôleurs.
     */
    public static function controllersDir(): string
    {
        return self::rootDir() . self::CONTROLLERS_DIR;
    }

    /**
     * Retourne le répertoire des utilitaires.
     *
     * @return string Chemin du répertoire des utilitaires.
     */
    public static function utilsDir(): string
    {
        return self::rootDir() . self::UTILS_DIR;
    }
}
