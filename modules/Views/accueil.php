<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil IUT</title>
    <link rel="stylesheet" href="/_assets/styles/accueil.css">
</head>
<body>
<nav class="secondHeader">
    <ul>
        <li><a href="/root.php?ctrl=Accueil">Accueil</a></li>
        <li><a href="/root.php?ctrl=Connexion">Connexion</a></li>
    </ul>
</nav>

<div class="popup">
    <div class="popup-content">
        <h2>"logo" et texte intro pop up</h2>
        <p>Fiche qui apparaît pour la pop-up.</p>
    </div>
</div>

<div class="news-container">
    <div class="news-card">
        <img src="image1.jpg" alt="Image">
        <h3>Titre ...</h3>
        <p>Début de l'actu...</p>
        <a href="#">Voir plus</a>
    </div>
    <div class="news-card">
        <img src="image2.jpg" alt="Image">
        <h3>Titre ...</h3>
        <p>Début de l'actu...</p>
        <a href="#">Voir plus</a>
    </div>
    <div class="news-card">
        <img src="image3.jpg" alt="Image">
        <h3>Titre ...</h3>
        <p>Début de l'actu...</p>
        <a href="#">Voir plus</a>
    </div>
    <div class="news-card">
        <img src="image4.jpg" alt="Image">
        <h3>Titre ...</h3>
        <p>Début de l'actu...</p>
        <a href="#">Voir plus</a>
    </div>
</div>

<div class="events">
    <h2>Événements</h2>
    <div class="timeline">
        <div class="timeline-arrow"></div>
        <div class="event-point past-event" data-event="Événement passé"></div>
        <div class="event-point past-event" data-event="Événement passé 2"></div>
        <div class="event-point current-event" data-event="Actu en cours"></div>
        <div class="event-point future-event" data-event="Événement futur"></div>
        <div class="event-point future-event" data-event="Événement futur 2"></div>
        <div class="event-point future-event" data-event="Événement futur 3"></div>
    </div>
    <div><a class="news-card" href="#">Voir tout les événnements</a></div>
</div>

<div class="moreInfo">
    <div class="info-section"><a href="/root.php?ctrl=Apropos">A propos</a></div>
    <div class="info-section"><a href="/root.php?ctrl=MentionsLegales">Mentions Légales</a></div>
    <div class="info-section"><a href="/root.php?ctrl=Bde">BDE</a></div>
    <div class="info-section"><a href="/root.php?ctrl=PresentationFiliere">Présentation des filières</a></div>
</div>

<script src="/_assets/scripts/accueil.js"></script>
</body>
</html>



