<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Hotels</title>
        <link href="<?php echo CONTEXT_PATH ?>/css/backend.css" rel="stylesheet" type="text/css" />
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
    <body id="hotels">
        <?php include 'includes/header.php'; ?>
        <div id="content">
            <ul class="tab">
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotel/orders">Orders</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotel/plans">Plans</a></li>
                <li><a class="active" href="<?php echo CONTEXT_PATH; ?>/backend/hotel/hotels">Hotels</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotel/report">Report</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotel/users">Users</a></li>
            </ul>
            <div class="normosa-ui-datatable" id="datatable_hotels">
                <div class="toolbar">
                    <a class="btn" action="new" href="<?php echo CONTEXT_PATH ?>/backend/hotel/hotels?action=new" title="New Hotel">New</a>
                    <a class="btn" action="edit" href="<?php echo CONTEXT_PATH ?>/backend/hotel/hotels?action=update" title="Edit Hotel">Edit</a>
                    <a class="btn" action="delete" href="<?php echo CONTEXT_PATH ?>/backend/hotel/hotels?action=delete" title="Delete Hotel">Delete</a>
                    <a class="btn" action="reload" href="<?php echo CONTEXT_PATH ?>/backend/hotel/hotels?action=reload" title="Reload Hotel">Reload</a>
                    <a class="fullscreen" action="fullscreen" title="Toggle FullScreen"></a>
                    <?php include 'includes/pagination.php'; ?>
                </div>
                <div class="datasource">
                    <select id="_disabled">
                        <option value="1">Disable</option>
                        <option value="0">Active</option>
                    </select>
                </div>
                <div class="scrollpane" style="height: 500px;">
                    <div class=content style="min-width:1450px;">
                        <div class=th>
                            <span class="td select" title="Select All"></span>
                            <span class="td w150" style="text-align:center" name="name" dataColumn="DataColumnTextbox" validation="String" min="2" max="30">Name</span>
                            <span class="td w80" style="text-align:center" name="phone" dataColumn="DataColumnTextbox" max="11">Phone</span>
                            <span class="td w120" style="text-align:center" name="monthly_amount" dataColumn="DataColumnTextbox" validation="Number">Monthly Amount</span>
                            <span class="td w300" style="text-align:center;" name="sms" dataColumn="DataColumnTextbox" validation="String" max="300">SMS</span>
                            <span class="td w120" style="text-align:center;" name="sms_sender_name" dataColumn="DataColumnTextbox" validation="String" max="11">SMS Sender Name</span>
                            <span class="td w80" style="text-align:center" name="disabled" dataColumn="DataColumnSelect" dataSource="_disabled">Active</span>
                            <span class="td w200" style="text-align:center" name="address" dataColumn="DataColumnTextbox" max="300">Address</span>
                            <span class="td w120" style="text-align:center">Creation Date</span>
                            <span class="td w120" style="text-align:center">Last Changed</span>
                        </div>
                        <div class=th_fix></div>
                        <?php require 'hotel-reload.php'; ?>
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