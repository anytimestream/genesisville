<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Tickets</title>
        <link href="<?php echo CONTEXT_PATH ?>/css/station.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONTEXT_PATH ?>/css/data.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONTEXT_PATH ?>/css/datagridview.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONTEXT_PATH ?>/js/ext/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo CONTEXT_PATH ?>/favicon.ico" type="image/ico" />
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/ext/jquery-1.6.4.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/ext/json.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/ext/jquery-ui-1.8.5.custom.min.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/core.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/datatable.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/form.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/history.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/anchor.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/validation.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/dialog.js" ></script>
    </head>
    <body id="tickets">
        <div id="content">
            <div class="left">
                <?php include_once 'includes/menu.php'; ?>
            </div>
            <div class="right">
                <div class=register>
                    <?php
                    $planGroups = $_GET['plan-groups'];
                    for ($i = 0; $i < count($planGroups); $i++) {
                        ?><div style="width:180px; float:left;"><?php
                        for ($p = 0; $p < $planGroups[$i]->count(); $p++) {
                            ?><a href="<?php echo CONTEXT_PATH.'/station/tickets?plan='.$planGroups[$i][$p]->getValue('name')?>">
                            <h3><?php echo $planGroups[$i][$p]->getValue('name') ?></h3>
                            <h4>N<?php echo number_format($planGroups[$i][$p]->getValue('amount'), 2)?></h4>
                            </a><?php
                        }
                        ?></div><?php
                    }
                    ?>
                    <div class=clear></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <iframe id="iframe_fix" style="display: none"></iframe>
        <script language="javascript" type="text/javascript">
            var _base = '<?php echo CONTEXT_PATH ?>/';
            $(document).ready(function(){
                _plugins = new NT.Core.hashTable();
                var _history = $n(document.body).history({});
            });
        </script>
    </body>
</html>