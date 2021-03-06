<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Payment History</title>
        <link href="<?php echo CONTEXT_PATH ?>/css/prepaid.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONTEXT_PATH ?>/css/data.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo CONTEXT_PATH ?>/favicon.ico" type="image/ico" />
        <link href="<?php echo CONTEXT_PATH ?>/css/datagridview.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONTEXT_PATH ?>/js/ext/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/ext/jquery-1.7.1.min.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/ext/json.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/ext/jquery-ui-1.8.5.custom.min.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/core.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/datatable.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/form.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/history.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/anchor.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/validation.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/dialog.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/extension.js" ></script>
    </head>
    <body id="tickets">
        <div id="content">
            <div class="left">
                <?php include_once 'includes/menu.php'; ?>
            </div>
            <div class="right">
                <div class="normosa-ui-datatable" id="datatable_payment_history">
                    <div class="toolbar">
                        <?php include 'includes/form-search-by-date.php'; ?>
                        <?php include 'includes/pagination.php'; ?>
                    </div>
                    <div class="datasource"></div>
                    <div class="scrollpane" style="height: 500px;">
                        <div class=content style="min-width:1050px;">
                            <div class=th>
                                <span class="td select" title="Select All"></span>
                                <span class="td w120" style="text-align:center">Plan</span>
                                <span class="td w80" style="text-align:center">Plan Status</span>
                                <span class="td w80" style="text-align:center">Amount</span>
                                <span class="td w80" style="text-align:center">Duration</span>
                                <span class="td w80" style="text-align:center">Plan Expiry</span>
                                <span class="td w80" style="text-align:center">Transaction Id</span>
                                <span class="td w120" style="text-align:center">Remarks</span>
                                <span class="td w100" style="text-align:center">Method</span>
                                <span class="td w120" style="text-align:center">Creation Date</span>
                            </div>
                            <div class=th_fix></div>
                            <?php require 'payment-history-reload.php'; ?>
                        </div>
                    </div>
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