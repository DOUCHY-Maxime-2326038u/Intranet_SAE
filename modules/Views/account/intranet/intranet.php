
<div class="container">
    <div id="sidebar">
      <div class="tab" onclick="loadContent('dashboard')">Tableau de bord</div>
      <div class="tab" onclick="loadContent('annonces')">Les Annonces</div>
      <div class="tab" onclick="loadContent('reservation')">Réservation de salle</div>
        <?php if ((new Intranet)->getUserRole($_SESSION['id_user'], $_SESSION['email_user']) === 'etudiant'): ?>
        <div class="tab" onclick="loadContent('bde')">BDE Carte</div>
        <?php endif; ?>
    </div>

    <div id="content">
        <?php if (isset($success) && $success): ?>
            <p class="success">Succès !</p>
        <?php elseif (isset($success)): ?>
            <p class="error">Échec. Veuillez réessayer.</p>
        <?php endif; ?>
        <h1>Bienvenue sur l'intranet</h1>
        <p>Sélectionnez un onglet pour voir le contenu correspondant.</p>
    </div>
</div>
<script>
    // Fonction pour charger dynamiquement le contenu
    function loadContent(action) {
        const xhr = new XMLHttpRequest();
        // La requête GET appelle IntranetController et passe l'action (comme tableau de bord, annonces, etc.)
        xhr.open('GET', 'root.php?ctrl=Intranet&action=' + action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
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

