
<?php
final class IntranetController
{
    private string $titre  = "intranet";

    public function defaultAction()
    {
        ViewHandler::show("intranet");
    }

    public function getTitre(): string
    {
        return $this -> titre;
    }
    public function getContentAction()
    {
        if (isset($_GET['tab'])) {
            $tab = $_GET['tab'];

            switch ($tab) {
                case 'dashboard':
                    echo "<h2>Tableau de bord</h2><p>Voici le contenu du tableau de bord.</p>";
                    break;
                case 'reservation':
                    echo "<h2>Reservation de salle</h2><p>le contenu de prout.</p>";
                    break;

                case 'annonce':
                    echo "<h2>Annonces</h2><p>Les annonces.</p>";
                    break;
                case 'professeur':
                    echo "<h2>Mes professeurs</h2><p>équipe pédagogique avec mail.</p>";
                    break;
                default:
                    echo "<p>Onglet non reconnu.</p>";
                    break;
            }
        } else {
            echo "<p>Aucun onglet sélectionné.</p>";
        }
    }
}
