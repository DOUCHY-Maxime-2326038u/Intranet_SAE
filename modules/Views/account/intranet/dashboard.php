    <div class="dashboard-container">
        <h1>Bienvenue sur votre Dashboard</h1>

        <!-- Annonce Section -->
        <?php if (isset($dashboardData['annonce'])): ?>
            <div class="card annonce">
                <h2>📢 Dernière Annonce</h2>
                <h3><?= htmlspecialchars($dashboardData['annonce']['TITRE']) ?></h3>
                <p><?= htmlspecialchars($dashboardData['annonce']['CONTENU']) ?></p>
                <p class="date">Publié le : <?= htmlspecialchars($dashboardData['annonce']['DATE_PUBLICATION']) ?></p>
            </div>
        <?php endif; ?>

        <!-- Réservation Section -->
        <?php if (isset($dashboardData['reservation'])): ?>
            <div class="card reservation">
                <h2>🛋️ Réservation Actuelle</h2>
                <p><strong>Salle :</strong> <?= htmlspecialchars($dashboardData['reservation']['NUM_SALLE']) ?></p>
                <p><strong>De :</strong> <?= htmlspecialchars($dashboardData['reservation']['DEBUT']) ?></p>
                <p><strong>À :</strong> <?= htmlspecialchars($dashboardData['reservation']['FIN']) ?></p>
            </div>
        <?php endif; ?>

        <!-- Emploi du Temps Section -->
        <?php if (isset($dashboardData['emploi_du_temps'])): ?>
        <div class="card emploi-du-temps">
            <h2>📅 Votre Emploi du Temps</h2>
            <div class="timetable">
                <table>
                    <thead>
                    <tr>
                        <th>Heures</th>
                        <th>Lundi</th>
                        <th>Mardi</th>
                        <th>Mercredi</th>
                        <th>Jeudi</th>
                        <th>Vendredi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Regrouper les cours par jour (1 = Lundi, 2 = Mardi, etc.)
                    $days = ['1' => [], '2' => [], '3' => [], '4' => [], '5' => []];
                    foreach ($dashboardData['emploi_du_temps'] as $cours) {
                        $dayOfWeek = date('N', strtotime($cours['DEBUT'])); // Jour de la semaine (1 à 7)
                        if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                            $days[$dayOfWeek][] = $cours;
                        }
                    }

                    // Pour éviter de réafficher des cellules « recouvertes » par un rowspan
                    // on va garder un tableau de booléens pour marquer les heures déjà occupées.
                    $occupied = [];
                    for ($d = 1; $d <= 5; $d++) {
                        for ($h = 7; $h <= 18; $h++) {
                            $occupied[$d][$h] = false;
                        }
                    }

                    // Parcourir chaque heure de 07:00 à 18:00
                    for ($hour = 7; $hour <= 18; $hour++): ?>
                        <tr>
                            <!-- Affichage de l'heure dans la première colonne -->
                            <td class="time"><?= sprintf("%02d:00", $hour) ?></td>

                            <?php
                            // Parcourir chaque jour (Lundi=1, ..., Vendredi=5)
                            for ($day = 1; $day <= 5; $day++):
                                // Si la case est déjà « recouverte » par un rowspan précédent
                                if ($occupied[$day][$hour]) {
                                    // On n'affiche pas de <td>, on passe à la colonne suivante
                                    continue;
                                }

                                // Par défaut, on considère qu'on n'affiche rien
                                $cellRendered = false;

                                // Parcourir les cours de ce jour
                                foreach ($days[$day] as $index => $cours) {
                                    $start = strtotime($cours['DEBUT']);
                                    $end = strtotime($cours['FIN']);
                                    $hourStart = (int) date('G', $start);  // heure de début (0 à 23)
                                    $duration = ($end - $start) / 3600;     // durée en heures (peut être décimal)
                                    // Arrondir la durée à l'entier supérieur
                                    $rowSpan = (int) ceil($duration);
                                    if ($rowSpan < 1) {
                                        $rowSpan = 1;
                                    }

                                    // Si le cours commence pendant cette heure-ci (exacte) et que le jour correspond
                                    if ($hourStart === $hour && date('N', $start) == $day) {
                                        echo "<td rowspan='{$rowSpan}'>
                                        <strong>" . htmlspecialchars($cours['NOM_COUR']) . "</strong><br>
                                        " . htmlspecialchars($cours['SALLE']) . "<br>
                                        " . date('H:i', $start) . " - " . date('H:i', $end) . "
                                    </td>";

                                        // Marquer les heures suivantes comme occupées par ce rowspan
                                        for ($h = $hour + 1; $h < $hour + $rowSpan; $h++) {
                                            if ($h <= 18) {
                                                $occupied[$day][$h] = true;
                                            }
                                        }

                                        $cellRendered = true;
                                        break; // Sortir du foreach des cours
                                    }
                                }

                                // Si aucun cours n'a été placé pour cette case, on affiche une cellule vide
                                if (!$cellRendered) {
                                    echo "<td></td>";
                                }

                            endfor; // Fin du for sur les jours ?>
                        </tr>
                    <?php endfor; // Fin du for sur les heures ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isset($dashboardData['poster_annonce'])): ?>
            <!-- Section pour poster une nouvelle annonce -->
            <div class="card poster-annonce">
                <h2>📝 Poster une Annonce</h2>
                <form method="POST" action="?ctrl=Intranet&action=poster">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? null; ?>">
                    <div class="form-group">
                        <label for="titre">Titre :</label>
                        <input type="text" id="titre" name="titre" required>
                    </div>
                    <div class="form-group">
                        <label for="contenu">Contenu :</label>
                        <textarea id="contenu" name="contenu" rows="4" required></textarea>
                    </div>
                    <button type="submit" name="action" value="poster_annonce">Publier</button>
                </form>
            </div>

        <?php endif; ?>
        <?php if (isset($dashboardData['statistiques'])): ?>
        <div class="card statistiques">
            <h2>📊 Vos Statistiques</h2>
            <table>
                <thead>
                <tr>
                    <th>Matière</th>
                    <th>Nombre d'Étudiants</th>
                    <th>Moyenne Générale</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dashboardData['statistiques'] as $stat): ?>
                    <tr>
                        <td><?= htmlspecialchars($stat['MATIERE']) ?></td>
                        <td><?= htmlspecialchars($stat['NB_ETUDIANTS']) ?></td>
                        <td><?= htmlspecialchars($stat['MOYENNE_GENERALE']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
