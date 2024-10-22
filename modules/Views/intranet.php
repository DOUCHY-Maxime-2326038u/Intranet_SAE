<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/_assets/styles/intranet.css" />
    <title>Intranet</title>
</head>
<body>
<div class="container">
    <div id="sidebar">
      <div class="tab" onclick="loadContent('dashboard')">Tableau de bord</div>
      <div class="tab" onclick="loadContent('annonce')">Les Annonces</div>
      <div class="tab" onclick="loadContent('professeur')">Mon équipe pédagogique</div>
      <div class="tab" onclick="loadContent('reservation')">Réservation de salle</div>
    </div>

    <div id="content">
        <h1>Bienvenue sur l'intranet</h1>
        <p>Sélectionnez un onglet pour voir le contenu correspondant.</p>
    </div>
</div>
<script>
    // Fonction pour charger dynamiquement le contenu
    function loadContent(tab) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'root.php?ctrl=intranet&action=getContent&tab=' + tab, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('content').innerHTML = xhr.responseText;
            } else {
                document.getElementById('content').innerHTML = 'Erreur lors du chargement.';
            }
        };
        xhr.send();
    }
</script>

</body>
</html>
