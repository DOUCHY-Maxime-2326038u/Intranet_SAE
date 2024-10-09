<?php
require 'includes/Autoloader.php';

Autoloader::autoload('Accueil');
Autoloader::autoload('MentionsLegalesController');
Autoloader::autoload('PresentationFiliereController');

$db = new Database('intraiut_1');

if (isset($_GET['page']) && $_GET['page'] === 'mentions_legales') {
    $controller = new MentionsLegalesController();
    $controller->afficherPage();
} elseif (isset($_GET['page']) && $_GET['page'] === 'presentation_filiere') {
    // Afficher la page présentation des filières
    $controller = new PresentationFiliereController();
    $controller->afficherPage();
} else {
    // Page d'accueil par défaut
    $accueil = new Accueil();
    $accueil->afficher();
}
?>



