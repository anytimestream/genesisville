<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>New Order</title>
        <link href="<?php echo CONTEXT_PATH ?>/css/hotel.css" rel="stylesheet" type="text/css" />
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
                <form method="post" class="ticket-info">
                    <div class=row>
                        <center><label style="padding: 5px 0;color:#eff;font-size:15px; font-weight: bold; display:block "><?php $_GET['plan']->getValue('description') . ' - N' . $_GET['plan']->getValue('amount') ?> </label></center>
                    </div>
                    <div class=row>
                        <label class="label w100">Phone</label><input style="margin-left: 5px;" inputtype="textbox" class="textbox w200" name="phone" id="phone" value="<?php $_GET['phone'] ?>">
                    </div>
                    <?php
                    if (isset($_POST['phone'])) {
                        if (strlen($_POST['phone']) < 11 || substr($_POST['phone'], 0, 1) != "0" || !is_numeric($_POST['phone'])) {
                            ?><center><span style="display:inline-block;line-height: 25px;color:#00ff00">Invalid phone number</span></center><?php
                }
            }
                    ?>
                    <div class=row>
                        <label class="label w100" style="float:left;">Receive Method</label>
                        <label class="label w40" style="float:left;">SMS</label>
                        <input type="radio" style="float:left;margin: 5px 0 0 5px;" checked inputtype="_default" name="method" id="method" value="SMS"/>
                        <label class="label w50" style="float:left;">Print</label>
                        <input type="radio" style="margin: 5px 0 0 5px;float:left;" inputtype="_default" name="method" id="method" value="print"/>
                        <div class="clear"></div>
                    </div>
                    <div class="row">
                        <center><button style="margin-top: 5px;padding: 0 10px;">Send</button></center>
                    </div>
                </form>
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