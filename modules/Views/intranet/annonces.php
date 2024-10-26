<h1>Les annonces</h1>

<?php if (empty($A_params["annonces"])): ?>
    <p>Aucune annonce disponible pour le moment.</p>
<?php else: ?>
    <ul>
        <?php foreach ($A_params["annonces"] as $annonce): ?>
            <li>
                <h2><?php echo htmlspecialchars($annonce->professeur_prenom); ?> <?php echo htmlspecialchars($annonce->professeur_nom); ?></h2>
                <h3><?php echo htmlspecialchars($annonce->titre ?? 'Sans titre', ); ?></h3>
                <p><?php echo htmlspecialchars($annonce->contenu ?? ''); ?></p>
                <small>Publié le : <?php echo htmlspecialchars($annonce->date_publication ?? 'Date inconnue'); ?></small><br>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>