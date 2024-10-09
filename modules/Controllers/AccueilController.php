
<?php
final class AccueilController
{
    private string $titre  = "Accueil";
    
    public function defaultAction()
    {
        ViewHandler::show("Accueil");
    }

    public function getTitre(): string
    {
        return $this -> titre;
    }
}
