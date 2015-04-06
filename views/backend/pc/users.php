<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Plantation City | Users</title>
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
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/extension.js" ></script>
    </head>
    <body id="users">
        <?php include 'includes/header.php'; ?>
        <div id="content">
            <ul class="tab">
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/plantation-city/orders">Orders</a></li>
                <li><a class="active" href="<?php echo CONTEXT_PATH; ?>/backend/plantation-city/users">Users</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/plantation-city/plans">Plans</a></li>
            </ul>
            <div class="normosa-ui-datatable" id="datatable_users">
                <div class="toolbar">
                    <a class="btn" action="edit" href="<?php echo CONTEXT_PATH ?>/backend/plantation-city/users?action=update" title="Edit User">Edit</a>
                    <a class="btn" action="reload" href="<?php echo CONTEXT_PATH ?>/backend/plantation-city/users?action=reload" title="Reload User">Reload</a>
                    <a class="btn" execute="send_sms" href="<?php echo CONTEXT_PATH ?>/backend/plantation-city/users?action=update-password" title="Update Password" style="padding: 0 7px;">Update Password</a>
                    <a class="fullscreen" action="fullscreen" title="Toggle FullScreen"></a>
                    <?php include 'includes/pagination.php'; ?>
                </div>
                <div class="datasource">
                    
                </div>
                <div class="scrollpane" style="height: 500px;">
                    <div class=content style="min-width:1200px;">
                        <div class=th>
                            <span class="td select" title="Select All"></span>
                            <span class="td w50" style="text-align:center">User Id</span>
                            <span class="td w120">Current Plan</span>
                            <span class="td w80">Expiry</span>
                            <span class="td w80" name="phone" dataColumn="DataColumnTextbox" validation="String" min="11"  max="11" style="text-align:center;">Phone</span>
                            <span class="td w150" name="email" dataColumn="DataColumnTextbox" validation="Email" allownull="true"  max="200" style="text-align:center;">Email</span>
                            <span class="td w150" name="name" dataColumn="DataColumnTextbox" validation="String" min="4"  max="40" style="text-align:center;">Name</span>
                            <span class="td w180" name="address" dataColumn="DataColumnTextbox" validation="String"  max="200" style="text-align:center;">Address</span>
                            <span class="td w80" name="city" dataColumn="DataColumnTextbox" validation="String" max="20" style="text-align:center;">City</span>
                            <span class="td w120" style="text-align:center">Creation Date</span>
                            <span class="td w120" style="text-align:center">Last Changed</span>
                        </div>
                        <div class=th_fix></div>
                        <?php require 'user-reload.php'; ?>
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