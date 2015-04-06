<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title><?php echo $_GET['name']?></title>
        <link href="<?php echo CONTEXT_PATH ?>/css/prepaid.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONTEXT_PATH ?>/css/data.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo CONTEXT_PATH ?>/favicon.ico" type="image/ico" />
    </head>
    <body id="tickets">
        <div id="content">
            <div class="left">
                <?php include_once 'includes/menu.php'; ?>
            </div>
            <div class="right" style="margin-top: 160px;">
                <div class="tab">
                    <h3>User Id</h3>
                    <span><?php echo UserService::GetUser()->getValue('username')?></span>
                </div>
                <div class="tab">
                    <h3>Current Plan</h3>
                    <span><?php echo $_GET['info']['plan']?></span>
                </div>
                <div class="tab">
                    <h3>Expiry Date</h3>
                    <span><?php echo Date::convertFromMySqlDate($_GET['info']['expires'])?></span>
                </div>
                <div class="clear"></div>
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