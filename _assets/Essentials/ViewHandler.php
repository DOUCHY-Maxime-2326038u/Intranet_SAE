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

    public static function show(string $S_loc): void
    {
        $S_file = Constants::viewsDir() . $S_loc . '.php';

        if (!is_readable($S_file)) {
            throw new Exception("Fichier de vue non trouvé : " . $S_file);
        }

        ob_start();
        include $S_file;
        ob_end_flush();
    }
}
