
<?php
final class PresentationFiliereController
{
    private string $titre  = "presentation_filiere";

    public function defaultAction()
    {
        ViewHandler::show("informational/presentation_filiere");
    }

    public function getTitre(): string
    {
        return $this -> titre;
    }
}
