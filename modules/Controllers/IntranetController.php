
<?php
final class IntranetController
{
    private string $titre  = "intranet";

    public function defaultAction()
    {
        ViewHandler::show("intranet");
    }

    public function getTitre(): string
    {
        return $this -> titre;
    }
}
