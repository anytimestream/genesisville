<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>GenesisVille Ltd | Login</title>
        <link href="<?php echo CONTEXT_PATH ?>/css/signup.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="content">
            <div class="name"></div>
            <?php
            $plans = $_GET['plans'];
            for ($i = 0; $i < $plans->count(); $i++) {
                ?>
                <div class="plan">
                    <a href="<?php echo CONTEXT_PATH . '/prepaid/signup?plan=' . $plans[$i]->getValue('name') ?>">
                        <h3><?php echo $plans[$i]->getValue('name') ?></h3>
                        <h4>N<?php echo number_format($plans[$i]->getValue('amount'), 2) ?></h4>
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </body>
</html>
