<!doctype html>
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href=<?php echo $A_params['css']?>>
        
        <title><?php echo isset($A_params['titre']) ? $A_params['titre'] : 'Titre par défaut'; ?></title>
    </head>
    <body>
        <?php if (!isAjaxRequest())ViewHandler::show('pattern/header');?>
        <?php echo $A_params['body'] ?>
        <?php if (!isAjaxRequest())ViewHandler::show('pattern/footer'); ?>
        <script src="_assets/scripts/pattern.js"></script>
        <script src=<?php echo $A_params['js']?>></script>
    </body>
</html>