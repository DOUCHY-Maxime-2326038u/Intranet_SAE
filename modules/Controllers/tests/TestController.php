
<?php
final class TestController
{
    private string $titre  = "Test";
    private array $params = [];

    public function __construct()
    {
        $this->params['titre'] = $this->titre;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function defaultAction()
    {
        ViewHandler::show("tests/test", $this->params);
    }
}
