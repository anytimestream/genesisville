<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Hotel | Orders</title>
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
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/extension.js" ></script>
    </head>
    <body id="orders">
        <?php include 'includes/header.php'; ?>
        <div id="content">
            <ul class="tab">
                <li><a class="active" href="<?php echo CONTEXT_PATH; ?>/backend/hotel/orders">Orders</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotel/plans">Plans</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotel/hotels">Hotels</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotel/report">Report</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotel/users">Users</a></li>
            </ul>
            <div class="normosa-ui-datatable" id="datatable_orders">
                <div class="toolbar">
                    <a class="btn" action="new" href="<?php echo CONTEXT_PATH ?>/backend/hotel/orders?action=summary&<?php echo $_GET['url_search'] ?>" title="<?php echo $_GET['summary_url'] ?>">Summary</a>
                    <a class="btn" execute="send_sms" href="<?php echo CONTEXT_PATH ?>/backend/hotel/orders?action=send-sms" title="Send SMS">Send SMS</a>
                    <a class="btn" action="reload" href="<?php echo CONTEXT_PATH ?>/backend/hotel/orders?action=reload" title="Reload Plan">Reload</a>
                    <?php include 'includes/form-search-by-date-and-hotel.php'; ?>
                    <a class="fullscreen" action="fullscreen" title="Toggle FullScreen"></a>
                    <?php include 'includes/pagination.php'; ?>
                </div>
                <div class="datasource"></div>
                <div class="scrollpane" style="height: 500px;">
                    <div class=content style="min-width:1200px;">
                        <div class=th>
                            <span class="td select" title="Select All"></span>
                            <span class="td w120" style="text-align:center">Plan</span>
                            <span class="td w80" style="text-align:center">Phone</span>
                            <span class="td w80" style="text-align:center">Amount</span>
                            <span class="td w100" style="text-align:center">Transaction Id</span>
                            <span class="td w120" style="text-align:center">Hotel</span>
                            <span class="td w120" style="text-align:center">User</span>
                            <span class="td w120" style="text-align:center">Creation Date</span>
                            <span class="td w120" style="text-align:center">Last Changed</span>
                        </div>
                        <div class=th_fix></div>
                        <?php require 'order-reload.php'; ?>
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