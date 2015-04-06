<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Hotspot | Plans</title>
        <link href="<?php echo CONTEXT_PATH ?>/css/backend.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONTEXT_PATH ?>/css/data.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONTEXT_PATH ?>/css/datagridview.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONTEXT_PATH ?>/js/ext/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo CONTEXT_PATH ?>/favicon.ico" type="image/ico" />
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
    </head>
    <body id="plans">
        <?php include 'includes/header.php'; ?>
        <div id="content">
            <ul class="tab">
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotspot/orders">Orders</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotspot/pending-orders">Pending Orders</a></li>
                <li><a class="active" href="<?php echo CONTEXT_PATH; ?>/backend/hotspot/plans">Plans</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotspot/cards">Cards</a></li>
            </ul>
            <div class="normosa-ui-datatable" id="datatable_plans">
                <div class="toolbar">
                    <a class="btn" action="new" href="<?php echo CONTEXT_PATH ?>/backend/hotspot/plans?action=new" title="New Plan">New</a>
                    <a class="btn" action="edit" href="<?php echo CONTEXT_PATH ?>/backend/hotspot/plans?action=update" title="Edit Plan">Edit</a>
                    <a class="btn" action="delete" href="<?php echo CONTEXT_PATH ?>/backend/hotspot/plans?action=delete" title="Delete Plan">Delete</a>
                    <a class="btn" action="reload" href="<?php echo CONTEXT_PATH ?>/backend/hotspot/plans?action=reload" title="Reload Plan">Reload</a>
                    <a class="fullscreen" action="fullscreen" title="Toggle FullScreen"></a>
                    <?php include 'includes/pagination.php';?>
                </div>
                <div class="datasource"></div>
                <div class="scrollpane" style="height: 500px;">
                    <div class=content style="min-width:1200px;">
                        <div class=th>
                            <span class="td select" title="Select All"></span>
                            <span class="td w100" style="text-align:center" name="name" dataColumn="DataColumnTextbox" validation="String" min="2" max="30">Name</span>
                            <span class="td w70" style="text-align:center" name="group" dataColumn="DataColumnTextbox" validation="String" min="2" max="30">Group</span>
                            <span class="td w70" style="text-align:center" name="amount" dataColumn="DataColumnTextbox" validation="Number">Amount</span>
                            <span class="td w200" style="text-align:center" name="description" dataColumn="DataColumnTextbox" validation="String" min="2" max="300">Description</span>
                            <span class="td w40" style="text-align:center" name="notify" dataColumn="DataColumnTextbox" validation="Number">Notify</span>
                            <span class="td w150" style="text-align:center" name="uptime" dataColumn="DataColumnTextbox" validation="String" max="100">Uptime</span>
                            <span class="td w80" style="text-align:center" name="validity" dataColumn="DataColumnTextbox" validation="String" max="100">Validity</span>
                            <span class="td w60" style="text-align:center">Available</span>
                            <span class="td w50" style="text-align:center">Sold</span>
                            <span class="td w120" style="text-align:center">Creation Date</span>
                            <span class="td w120" style="text-align:center">Last Changed</span>
                        </div>
                        <div class=th_fix></div>
                        <?php require 'plan-reload.php'; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'includes/footer.php'; ?>
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