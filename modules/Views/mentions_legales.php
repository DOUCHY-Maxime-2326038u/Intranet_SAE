<?php
// Variables pour les informations légales
$editeur_nom = "IUT d'Aix-en-Provence";
$editeur_adresse = "413 Avenue Gaston Berger, 13625 Aix-en-Provence Cedex 01";
$editeur_tel = "+33 (0)4 13 94 63 90";
$editeur_email = "contact@iut-aix-univ.fr";

$hebergeur_nom = "OVHcloud";
$hebergeur_adresse = "2 rue Kellermann, 59100 Roubaix, France";
$hebergeur_tel = "+33 (0)9 72 10 10 07";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions Légales</title>
    <link rel="stylesheet" href="/_assets/styles/mentions_legales.css">
</head>
<body>
<header>
    <nav class="secondHeader">
        <ul>
            <li><a href="/root.php?ctrl=Accueil">Accueil</a></li>
            <li><a href="/root.php?ctrl=Connexion">Connexion</a></li>
            <li><a href="/root.php?ctrl=Bde">Bde</a></li>
            <li><a href="/root.php?ctrl=MentionsLegales">Mentions Légales</a></li>
            <li><a href="/root.php?ctrl=PresentationFiliere">Présentation des filières</a></li>

        </ul>
    </nav>
</header>
<h1>Mentions légales</h1>

<h2>Panneau de gestion des cookies</h2>
<p>Ce site utilise des cookies et vous donne le contrôle sur ceux que vous souhaitez activer.</p>
<button>Paramétrer mes cookies</button>

<ul>
    <li><a href="#recherche">Accéder à la recherche</a></li>
    <li><a href="#menu-principal">Accéder au menu principal</a></li>
    <li><a href="#contenu">Accéder au contenu</a></li>
    <li><a href="#pied-de-page">Accéder au pied de page</a></li>
</ul>

<h2>Éditeur du site</h2>
<p>
    Le site web du département Informatique de l'IUT d'Aix-en-Provence est édité par :<br>
    <?php echo $editeur_nom; ?><br>
    <?php echo $editeur_adresse; ?><br>
    Tél. : <?php echo $editeur_tel; ?><br>
    Email : <a href="mailto:<?php echo $editeur_email; ?>"><?php echo $editeur_email; ?></a>
</p>

<h2>Hébergeur du site</h2>
<p>
    Le site est hébergé par :<br>
    <?php echo $hebergeur_nom; ?><br>
    <?php echo $hebergeur_adresse; ?><br>
    Tél. : <?php echo $hebergeur_tel; ?>
</p>

<h2>Propriété intellectuelle</h2>
<p>
    Tous les contenus présents sur ce site (textes, images, illustrations, photographies, etc.) sont protégés par le droit d'auteur. Toute reproduction ou représentation sans autorisation préalable est interdite.
</p>

<h2>Responsabilité</h2>
<p>
    L'éditeur du site ne peut être tenu responsable des erreurs ou omissions présentes sur le site. L'utilisation des informations disponibles sur ce site est sous la seule responsabilité de l'utilisateur.
</p>

<h2>Utilisation des données personnelles</h2>
<p>
    Conformément au Règlement général sur la protection des données (RGPD), les données collectées sur ce site (nom, adresse email, etc.) sont utilisées uniquement pour les finalités prévues. Vous avez le droit d'accéder, de rectifier et de demander la suppression de vos données. Contactez-nous à : <a href="mailto:<?php echo $editeur_email; ?>"><?php echo $editeur_email; ?></a> pour exercer vos droits.
</p>

<h2>Cookies</h2>
<p>
    Ce site utilise des cookies pour améliorer l'expérience utilisateur. Vous pouvez choisir les cookies à activer ou désactiver à tout moment via le panneau de gestion des cookies.
</p>
<button>Paramétrer mes cookies</button>

<h2>Informations légales complémentaires</h2>
<p>
    En cas d'activité commerciale ou artisanale, veuillez inclure ici le numéro RCS/RNE, le numéro de TVA intracommunautaire si applicable, ainsi que les conditions générales de vente (CGV) si le site est marchand.
</p>

</body>
</html>
