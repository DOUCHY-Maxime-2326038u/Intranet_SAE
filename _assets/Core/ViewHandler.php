<?php

/**
 * Class ViewHandler
 *
 * Gère le rendu des vues de l'application.
 */
final class ViewHandler
{
    /**
     * Démarre la mise en mémoire tampon de la vue.
     *
     * @return void
     */
    public static function bufferStart(): void
    {
        ob_start();
    }

    /**
     * Récupère et nettoie le contenu du tampon de sortie.
     *
     * @return string Contenu capturé.
     */
    public static function bufferCollect(): string
    {
        return ob_get_clean();
    }

    /**
     * Affiche la vue spécifiée.
     *
     * Charge le fichier de vue correspondant au chemin fourni et extrait les paramètres s'ils sont définis.
     *
     * @param string $loc Localisation ou nom du fichier de vue (sans extension).
     * @param ViewParams|null $params Instance de ViewParams contenant les paramètres à extraire dans la vue.
     * @return void
     * @throws Exception Si le fichier de vue n'est pas lisible.
     */
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
