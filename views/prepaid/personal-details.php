<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Personal Details</title>
        <link href="<?php echo CONTEXT_PATH ?>/css/prepaid.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONTEXT_PATH ?>/css/data.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo CONTEXT_PATH ?>/favicon.ico" type="image/ico" />
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/ext/jquery-1.6.4.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/core.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/form.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/history.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/validation.js" ></script>
    </head>
    <body id="tickets">
        <div id="content">
            <div class="left">
                <?php include_once 'includes/menu.php'; ?>
            </div>
            <div class="right">
                <br/><br/><br/><br/><br/><br/><br/>
                <form class="normosa-ui-form" method="post" action="">
                    <div class="row" style="color:#00ff00; padding-left: 100px; height: 30px;min-height: 30px">
                        <?php if (isset($_GET['msg'])){echo $_GET['msg'];}?>
                    </div>
                    <div class="row">
                        <label class="label" for="name">Name</label>
                        <input inputtype="textbox" id="name" name="name" class="textbox w200" validation="String" min="2" maxlength=30" value="<?php echo $_GET['user']->getValue('name')?>"/>
                        <span class="error" style="display: block;padding-left: 110px; color: #00ff00;font-size: 11px;"></span>
                    </div>
                    <div class="row">
                        <label class="label" for="phone">Phone</label>
                        <input inputtype="textbox" id="phone" name="phone" class="textbox w200" validation="String" min="11" maxlength="11" value="<?php echo $_GET['user']->getValue('phone')?>"/>
                        <span class="error" style="display: block;padding-left: 110px; color: #00ff00;font-size: 11px;"></span>
                    </div>
                    <div class="row">
                        <label class="label" for="email">Email</label>
                        <input inputtype="textbox" id="email" name="email" class="textbox w200" validation="Email" allownull="true" maxlength="200" value="<?php echo $_GET['user']->getValue('email')?>"/>
                        <span class="error" style="display: block;padding-left: 110px; color: #00ff00;font-size: 11px;"></span>
                    </div>
                    <div class="row">
                        <label class="label" for="address">Address</label>
                        <input inputtype="textbox" id="address" name="address" class="textbox w200" validation="String" min="2" maxlength="300" value="<?php echo $_GET['user']->getValue('address')?>"/>
                        <span class="error" style="display: block;padding-left: 110px; color: #00ff00;font-size: 11px;"></span>
                    </div>
                    <div class="row">
                        <label class="label" for="city">City</label>
                        <input inputtype="textbox" id="city" name="city" class="textbox w200" validation="String" min="2" maxlength="20" value="<?php echo $_GET['user']->getValue('city')?>"/>
                        <span class="error" style="display: block;padding-left: 110px; color: #00ff00;font-size: 11px;"></span>
                    </div>
                    <div class="row" style="width: 300px">
                        <center><button>Update</button></center>
                    </div>
                </form>
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