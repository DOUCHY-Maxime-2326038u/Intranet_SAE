<?php

final class Constants
{
    const VIEWS_DIR = '/modules/Views/';
    const MODELS_DIR = '/modules/Models/';
    const CONTROLLERS_DIR = '/modules/Controllers/';
    const ESSENTIALS_DIR = '/_assets/Core/';

    const UTILS_DIR = '/_assets/Utils/';

    public static function rootDir(): string
    {
        return realpath(__DIR__ . '/../../');
    }

    public static function essentialsDir(): string
    {
        return self::rootDir() . self::ESSENTIALS_DIR;
    }

    public static function viewsDir(): string
    {
        return self::rootDir() . self::VIEWS_DIR;
    }

    public static function modelsDir(): string
    {
        return self::rootDir() . self::MODELS_DIR;
    }

    public static function controllersDir(): string
    {
        return self::rootDir() . self::CONTROLLERS_DIR;
    }

    public static function utilsDir(): string
    {
        return self::rootDir() . self::UTILS_DIR;
    }

}
