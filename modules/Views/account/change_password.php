<div class="change-password-box">
    <h2>Changer le mot de passe</h2>

    <!-- Si erreur définie, affiche en rouge -->
    <?php if (isset($erreur)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($erreur); ?></p>
    <?php endif; ?>

    <!-- Formulaire pour changer le mot de passe -->
    <form action="/root.php?ctrl=Connexion&action=changePassword" method="post">
        <!-- Champ pour entrer le nouveau mot de passe -->
        <label for="nouveau_mot_de_passe">Nouveau mot de passe</label>
        <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" required><br>

        <!-- Bouton pour confirmer -->
        <button type="submit" name="changer_mot_de_passe">Changer le mot de passe</button>
    </form>
</div>

