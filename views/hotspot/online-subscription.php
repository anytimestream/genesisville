<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>GenesisVille Hotspot</title>
        <link href="<?php echo CONTEXT_PATH ?>/css/hotspot.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONTEXT_PATH ?>/css/hotspot-data.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo CONTEXT_PATH ?>/favicon.ico" type="image/ico" />
    </head>
    <body>
        <?php include 'includes/header.php'; ?>
        <div id="content">
            <?php include 'includes/'.$_GET['view'] ?>
        </div>
        <?php include 'includes/footer.php'; ?>
    </body>
</html>