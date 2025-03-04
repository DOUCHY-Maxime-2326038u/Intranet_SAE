<?php
require '_assets/Core/Autoloader.php';
// Ajout des en-têtes de sécurité
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self'; img-src 'self' ; frame-src 'self' https://www.youtube.com/ ; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");

// Configuration des sessions
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.gc_maxlifetime', 3600);

session_start();
// Protection CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$allowedControllers = ['Connexion', 'Intranet', 'Accueil', 'Apropos', 'Bde', 'Test', 'Question'];
$allowedActions = ['default', 'login', 'changePassword', 'logout', 'dashboard', 'bde', 'poster', 'annonces', 'professeur', 'reservation', 'error', 'reviewQuestions', 'majQuestion', 'ajouter', 'supprimerQuestion', null];

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

if (!in_array($S_controller, $allowedControllers) || !in_array($S_action, $allowedActions)) {
    http_response_code(403);
    die("Accès non autorisé");
}



