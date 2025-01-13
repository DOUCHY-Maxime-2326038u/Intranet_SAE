
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
                <form method="POST" action="?ctrl=Intranet&action=reservation">
                    <input type="hidden" name="id_salle" value="<?= $salle['ID_SALLE'] ?>">
                    <label for="start_date_<?= $salle['ID_SALLE'] ?>">Début :</label>
                    <input type="datetime-local" id="start_date_<?= $salle['ID_SALLE'] ?>" name="debut" required>

                    <label for="end_date_<?= $salle['ID_SALLE'] ?>">Fin :</label>
                    <input type="datetime-local" id="end_date_<?= $salle['ID_SALLE'] ?>" name="fin" required>

                    <button type="submit">Réserver</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
