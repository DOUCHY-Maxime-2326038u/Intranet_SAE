<?php

class AproposController
{
    public function index()
    {
        $params = [
            'page_title' => 'À propos de notre IUT',
        ];

        ViewHandler::show('apropos', $params);
    }
}
?>