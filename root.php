<?php
require '_assets/Essentials/Autoloader.php';

header_remove("Server");
header_remove("Via");
header_remove("Host");

session_start();

// Protection CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


// Fonction pour déterminer si une requête est de type AJAX
function isAjaxRequest(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

$S_controller = $_GET['ctrl'] ?? null;
$S_action = $_GET['action'] ?? null;

// Ouvre le tampon d'affichage pour stocker la sortie
ViewHandler::bufferStart();

// Exécution du contrôleur et de l'action
$C_controller = new ControllerHandler($S_controller, $S_action);
$C_controller->execute();

// Récupère le contenu tamponné
$contenuPourAffichage = ViewHandler::bufferCollect();

// Si c'est une requête AJAX, renvoyer seulement le contenu partiel au format JSON
if (isAjaxRequest()) {
    echo $contenuPourAffichage;
} else {
    // Sinon, on affiche le gabarit complet avec le contenu
    ViewHandler::show('pattern', [
        'body' => $contenuPourAffichage
    ]);
}



