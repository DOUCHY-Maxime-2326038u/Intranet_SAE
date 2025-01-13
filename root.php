<?php
require '_assets/Essentials/Autoloader.php';
// Ajout des en-têtes de sécurité
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self'; img-src 'self' ; frame-src 'self' https://www.youtube.com/ ; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");

session_start();
// Protection CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$S_controller = $_GET['ctrl'] ?? null;
$S_action = $_GET['action'] ?? null;

// Ouvre le tampon d'affichage pour stocker la sortie
ViewHandler::bufferStart();

// Exécution du contrôleur et de l'action
$C_controller = new ControllerHandler($S_controller, $S_action);
$C_controller->execute();

// Récupère le contenu tamponné
$displayContent = ViewHandler::bufferCollect();
$params = $C_controller->getParams();

$params->set('body', $displayContent);
ViewHandler::show('pattern', $params);




