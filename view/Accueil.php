<?php
require 'includes/Database.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, maximum-scale=1" />
    <meta charset="UTF-8" />
    <!--<link rel="stylesheet" href="../_assets/styles/club.css" />-->
    <!--<link rel="icon" href="../_assets/images/lego.ico" />-->
    <title></title>
</head>
<body>

<header>
    <!--<img class="imgheader" alt="Logo" src="../_assets/images/lego.ico" />-->
    <!-- Menu de navigation -->
    <nav>
        <ul>
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="../index.php?page=club">Club</a></li>
            <li><a href="../index.php?page=repas">Repas</a></li>
            <li><a href="../index.php?page=plat">Plat</a></li>
        </ul>
    </nav>
</header>

<main>

        <ul>
            <?php foreach($db->query('SELECT * FROM test') as $row):?>
                <li><?= $row->id_test; ?></li>
            <?php endforeach; ?>

        </ul>


</main>

<footer>


</footer>

</body>
</html>

