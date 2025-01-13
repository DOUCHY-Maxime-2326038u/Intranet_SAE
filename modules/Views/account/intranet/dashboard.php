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
                        // Regrouper les cours par jour
                        $days = ['1' => [], '2' => [], '3' => [], '4' => [], '5' => []]; // Lundi à Vendredi
                        foreach ($dashboardData['emploi_du_temps'] as $cours) {
                            $dayOfWeek = date('N', strtotime($cours['DEBUT'])); // Jour de la semaine (1 à 7)
                            if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                                $days[$dayOfWeek][] = $cours;
                            }
                        }

                        // Afficher les horaires (08:00 à 18:00)
                        for ($hour = 7; $hour <= 18; $hour++): ?>
                            <tr>
                                <td class="time"><?= sprintf("%02d:00", $hour) ?></td>
                                <?php for ($day = 1; $day <= 5; $day++): ?>
                                    <?php
                                    $cellRendered = false;
                                    foreach ($days[$day] as $index => $cours):
                                        $start = strtotime($cours['DEBUT']);
                                        $end = strtotime($cours['FIN']);
                                        $duration = ($end - $start) / 3600;
                                        $hourStart = date('G', $start);

                                        // Vérification stricte : le cours commence à cette heure et correspond au jour
                                        if ($hourStart == $hour && date('N', $start) == $day) {
                                            echo "<td rowspan='{$duration}'>
                                    <strong>" . htmlspecialchars($cours['NOM_COUR']) . "</strong><br>
                                    " . htmlspecialchars($cours['SALLE']) . "<br>
                                    " . date('H:i', $start) . " - " . date('H:i', $end) . "
                                </td>";
                                            $cellRendered = true;
                                            break;
                                        }
                                    endforeach;

                                    // Si aucune cellule n'a été rendue, insérer une cellule vide
                                    if (!$cellRendered) {
                                        echo "<td></td>";
                                    }
                                    ?>
                                <?php endfor; ?>
                            </tr>
                        <?php endfor; ?>
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
