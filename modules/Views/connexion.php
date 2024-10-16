<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/_assets/styles/connexion.css" />
    <title>Connexion</title>
</head>
<body>

<header>
    <nav class="secondHeader">
        <ul>
            <li><a href="/root.php?ctrl=Accueil">Accueil</a></li>
            <li><a href="/root.php?ctrl=Bde">Bde</a></li>
            <li><a href="/root.php?ctrl=MentionsLegales">Mentions Légales</a></li>
            <li><a href="/root.php?ctrl=PresentationFiliere">Présentation des filières</a></li>
            <li><a href="/root.php?ctrl=Connexion">Connexion</a></li>

        </ul>
    </nav>
</header>

<!-- Boîte où l'utilisateur peut saisir ses informations pour se connecter -->
<div class="connexion-box">
    <h2>Connexion</h2>

    <!-- Si erreur définie, affiche en rouge -->
    <?php if (isset($erreur)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($erreur); ?></p>
    <?php endif; ?>

    <!-- Formulaire pour la connexion -->
    <form action="/root.php?ctrl=Connexion&action=login" method="post">
        <!-- Champ pour entrer email ou nom d'utilisateur -->
        <label for="identifiant">Email ou Nom d'utilisateur</label>
        <input type="text" id="identifiant" name="identifiant" required><br>

        <!-- Champ pour entrer le mot de passe -->
        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>

        <!-- Bouton de connexion -->
        <button type="submit" name="connexion">Se connecter</button>
    </form>
</div>

</body>
</html>
