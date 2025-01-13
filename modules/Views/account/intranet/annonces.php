<h1>Les annonces</h1>

<?php if (empty($annonces)): ?>
    <p>Aucune annonce disponible pour le moment.</p>
<?php else: ?>
    <ul>
        <?php foreach ($annonces as $annonce): ?>
        <div class="card annonce">
                <h2><?php echo htmlspecialchars($annonce->professeur_prenom); ?> <?php echo htmlspecialchars($annonce->professeur_nom); ?></h2>
                <h3><?php echo htmlspecialchars($annonce->titre ?? 'Sans titre', ); ?></h3>
                <p><?php echo htmlspecialchars($annonce->contenu ?? ''); ?></p>
                <small>Publié le : <?php echo htmlspecialchars($annonce->date_publication ?? 'Date inconnue'); ?></small><br>
        </div>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>