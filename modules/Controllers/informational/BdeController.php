
<?php
final class BdeController
{
    private string $titre  = "bde";

    public function defaultAction()
    {
        ViewHandler::show("informational/bde");
    }

    public function getTitre(): string
    {
        return $this -> titre;
    }
}
