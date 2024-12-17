<?php

final class ViewHandler
{
    public static function bufferStart(): void
    {
        ob_start();
    }

    public static function bufferCollect(): string
    {
        return ob_get_clean();
    }

    public static function show(string $loc, ViewParams $params = null): void
    {
        $S_file = Constants::viewsDir() . $loc . '.php';

        if (!is_readable($S_file)) {
            throw new Exception("Fichier de vue non trouvé : " . $S_file);
        }
        if ($params !== null) {
            extract($params->getAll());
        }
        ob_start();
        include $S_file;
        ob_end_flush();
    }
}
