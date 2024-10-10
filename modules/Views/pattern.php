<!doctype html>
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title></title>
    </head>
    <body>
        <?php ViewHandler::show('pattern/header');?>
        <?php echo $A_params['body'] ?>
        <?php ViewHandler::show('pattern/footer'); ?>
    </body>
</html>