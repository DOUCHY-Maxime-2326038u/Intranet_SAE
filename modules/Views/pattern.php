<!doctype html>
<html lang="fr">
    <?php if (!Request::isAjax()): ?>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/_assets/styles/pattern.css">
        <link rel="stylesheet" href=<?php echo $css ?? '/_assets/styles/pattern.css' ?>>
        <title><?php echo $titre ?? 'Titre par défaut'; ?></title>
    </head>
    <?php endif; ?>
    <body>
        <?php if (!Request::isAjax()) ViewHandler::show('pattern/header');?>
        <?php echo $body ?? 'Aucun contenu disponible.' ?>
        <?php if (!Request::isAjax()) ViewHandler::show('pattern/footer'); ?>
        <?php if (!Request::isAjax()): ?>
        <script src="/_assets/scripts/pattern.js"></script>
        <script src=<?php echo $js ?? '/_assets/scripts/pattern.js'?>></script>
    </body>
</html>
<?php endif; ?>