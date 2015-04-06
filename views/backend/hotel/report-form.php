<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Hotel | Report</title>
        <link href="<?php echo CONTEXT_PATH ?>/css/backend.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONTEXT_PATH ?>/css/data.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CONTEXT_PATH ?>/js/ext/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo CONTEXT_PATH ?>/favicon.ico" type="image/ico" />
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/ext/jquery-1.6.4.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/ext/jquery-ui-1.8.5.custom.min.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/core.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/form.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/history.js" ></script>
        <script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/validation.js" ></script>
    </head>
    <body id="report">
        <?php include 'includes/header.php'; ?>
        <div id="content">
            <ul class="tab">
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotel/orders">Orders</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotel/plans">Plans</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotel/hotels">Hotels</a></li>
                <li><a class="active" href="<?php echo CONTEXT_PATH; ?>/backend/hotel/report">Report</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/hotel/users">Users</a></li>
            </ul>
            <form id="form_stationreport" class="normosa-ui-form" action="<?php echo CONTEXT_PATH . '/backend/hotel/report' ?>" method="post" target="_blank">
                <div class="row">
                    <label class="label w100" for="hotel">Hotel</label>
                    <select inputtype="_default" id="hotel" name="hotel" class="textbox w200 h23">
                        <?php
                        $hotels = $_GET['hotels'];
                        for ($i = 0; $i < $hotels->count(); $i++) {
                            ?>
                            <option><?php echo $hotels[$i]->getValue('name') ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <label class="label w100" for="begin">Begin Date</label>
                    <input inputtype="date" changeMonth="true" numberOfMonths="1" format="d/mm/yy" id="begin" name="begin" value="<?php echo '01/'.date('m/Y') ?>"/>
                </div>
                <div class="row">
                    <label class="label w100" for="end">End Date</label>
                    <input inputtype="date" changeMonth="true" numberOfMonths="1" format="d/mm/yy" id="end" name="end" value="<?php echo '30/'.date('m/Y') ?>"/>
                </div>
                <center><button>Generate</button></center>
            </form>
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