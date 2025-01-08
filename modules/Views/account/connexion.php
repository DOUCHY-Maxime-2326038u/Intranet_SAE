<div class="body">
    <div class="box-deco2">caca</div>
    <div class="connexion-box">
        <h2>Connexion</h2>

        <!-- Si erreur définie, affiche en rouge -->
        <?php if (isset($erreur)): ?>
            <p style="color:red;"><?php echo htmlspecialchars($erreur); ?></p>
        <?php endif; ?>

        <!-- Formulaire pour la connexion -->
        <form action="/root.php?ctrl=Connexion&action=login" method="post">
            <!-- Champ pour entrer email ou nom d'utilisateur -->
            <label for="identifiant">Identifiant</label>
            <input type="text" id="identifiant" name="identifiant" required><br>

            <!-- Champ pour entrer le mot de passe -->
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>

            <!-- Bouton de connexion -->
            <button class="button" type="submit">Se connecter</button>
        </form>
    </div>
    <div class="box-deco1">caca</div>
</div>


