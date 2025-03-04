<?php

/**
 * Class ViewParams
 *
 * Stocke et gère les paramètres à transmettre aux vues.
 */
class ViewParams
{
    /**
     * @var array Tableau associatif des paramètres de la vue.
     */
    private array $params = [];

    /**
     * Définit une valeur pour un paramètre de vue.
     *
     * @param string $key Clé du paramètre.
     * @param mixed $value Valeur du paramètre.
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->params[$key] = $value;
    }

    /**
     * Récupère la valeur d'un paramètre de vue.
     *
     * @param string $key Clé du paramètre.
     * @param mixed $default Valeur par défaut si le paramètre n'existe pas.
     * @return mixed Valeur du paramètre ou valeur par défaut.
     */
    public function get(string $key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * Retourne tous les paramètres de vue sous forme de tableau associatif.
     *
     * @return array Tableau des paramètres de vue.
     */
    public function getAll(): array
    {
        return $this->params;
    }
}
