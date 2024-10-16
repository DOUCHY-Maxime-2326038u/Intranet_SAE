<?php
// Vue pour la page À propos
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="/_assets/styles/apropos.css">
</head>
<body>

<header>
    <nav class="secondHeader">
        <ul>
            <li><a href="/root.php?ctrl=Accueil">Accueil</a></li>
            <li><a href="/root.php?ctrl=Bde">Bde</a></li>
            <li><a href="/root.php?ctrl=Apropos">A Propos</a></li>
            <li><a href="/root.php?ctrl=MentionsLegales">Mentions Légales</a></li>
            <li><a href="/root.php?ctrl=PresentationFiliere">Présentation des filières</a></li>
            <li><a href="/root.php?ctrl=Connexion">Intranet</a></li>
        </ul>
    </nav>
</header>

<section>
    <h2>Présentation</h2>
    <p>
        Le département informatique de l'IUT d'Aix-en-Provence forme des informaticiens généralistes, capables de concevoir, développer, et sécuriser des systèmes d'information. Nous proposons deux parcours à partir de la deuxième année :
    </p>
    <ul>
        <li><strong>Réalisation d’applications :</strong> conception, développement, validation des logiciels.</li>
        <li><strong>Déploiement d’applications communicantes et sécurisées :</strong> cybersécurité, administration des systèmes et réseaux.</li>
    </ul>
    <p>
        Le département met un accent particulier sur l'acquisition de compétences techniques et théoriques, permettant aux étudiants d'intégrer le marché du travail ou de poursuivre des études.
    </p>
</section>

<section>
    <h2>Accès au département</h2>
    <p>
        Retrouvez facilement notre département grâce au plan interactif ci-dessous. Nous sommes situés au bâtiment B, juste à côté de l'entrée principale.
    </p>


    <div class="plan-container">
        <img src="/_assets/img/plan_iut.png" alt="Plan de l'IUT Aix-en-Provence" style="width:100%; height:auto;">
    </div>
</section>

<footer>
    <p>&copy; 2024 Département Informatique - IUT Aix-en-Provence</p>
</footer>

</body>
</html>
