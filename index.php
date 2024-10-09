<?php
require 'includes/Autoloader.php';

Autoloader::autoload('Accueil');
Autoloader::autoload('MentionsLegalesController');

$db = new Database('intraiut_1');

if (isset($_GET['page']) && $_GET['page'] === 'mentions_legales') {
    $controller = new MentionsLegalesController();
    $controller->afficherPage();
} else {
    $accueil = new Accueil();
    $accueil->afficher();
}
?>



