
<?php
final class MentionsLegalesController
{
    private string $titre  = "mentions_legales";

    public function defaultAction()
    {
        ViewHandler::show("informational/mentions_legales");
    }

    public function getTitre(): string
    {
        return $this -> titre;
    }
}
