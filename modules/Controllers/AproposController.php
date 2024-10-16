<?php

class AproposController
{
    public function index()
    {
        $params = [
            'page_title' => 'À propos de notre IUT',
            'description' => 'Le département informatique de l\'IUT d\'Aix-en-Provence forme des informaticiens généralistes...',
        ];

        ViewHandler::show('apropos', $params);
    }
}
?>