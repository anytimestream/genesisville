<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Station | Report</title>
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
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/station/orders">Orders</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/station/plans">Plans</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/station/stations">Stations</a></li>
                <li><a class="active" href="<?php echo CONTEXT_PATH; ?>/backend/station/report">Report</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/station/remittance">Remittance</a></li>
                <li><a href="<?php echo CONTEXT_PATH; ?>/backend/station/users">Users</a></li>
            </ul>
            <form id="form_stationreport" class="normosa-ui-form" action="<?php echo CONTEXT_PATH . '/backend/station/report' ?>" method="post" target="_blank">
                <div class="row">
                    <label class="label w100" for="station">Station</label>
                    <select inputtype="_default" id="station" name="station" class="textbox w200 h23">
                        <?php
                        $stations = $_GET['stations'];
                        for ($i = 0; $i < $stations->count(); $i++) {
                            ?>
                            <option><?php echo $stations[$i]->getValue('name') ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <label class="label w100" for="begin">Begin Date</label>
                    <input inputtype="date" id="begin" name="begin" value="<?php echo '01/' . date('m/Y') ?>"/>
                </div>
                <div class="row">
                    <label class="label w100" for="end">End Date</label>
                    <input inputtype="date" id="end" name="end" value="<?php echo date('d/m/Y', (mktime(0, 0, 0, date('m'), date('d'), date('Y')) + 24 * 60 * 60)) ?>"/>
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