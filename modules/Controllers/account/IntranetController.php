
<?php
final class IntranetController
{
    private string $titre  = "intranet";

    public function defaultAction()
    {
        ViewHandler::show("account/intranet");
    }

    public function getTitre(): string
    {
        return $this -> titre;
    }
}
