<?php
final class AproposController
{
    private string $titre  = "apropos";

    public function defaultAction()
    {
        $params = [
            'page_title' => 'À propos de notre IUT',
            'description' => 'Le département informatique de l\'IUT d\'Aix-en-Provence forme des informaticiens généralistes...',
        ];
        ViewHandler::show("informational/aPropos");
    }

    public function getTitre(): string
    {
        return $this -> titre;
    }
}