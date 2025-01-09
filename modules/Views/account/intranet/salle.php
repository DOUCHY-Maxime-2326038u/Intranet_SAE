<?php if (isset($reservation_success) && $reservation_success): ?>
    <p class="success">Réservation effectuée avec succès !</p>
<?php elseif (isset($reservation_success)): ?>
    <p class="error">Échec de la réservation. Veuillez réessayer.</p>
<?php endif; ?>

<table>
    <thead>
    <tr>
        <th>Numéro</th>
        <th>Capacité</th>
        <th>PC Disponible</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($salles as $salle): ?>
        <tr>
            <td><?= htmlspecialchars($salle['NUM_SALLE']) ?></td>
            <td><?= htmlspecialchars($salle['CAPACITE']) ?></td>
            <td><?= $salle['PC_SALLE'] ? 'Oui' : 'Non' ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id_salle" value="<?= $salle['ID_SALLE'] ?>">
                    <input type="datetime-local" name="date_reservation" required>
                    <button type="submit">Réserver</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>