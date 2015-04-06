<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Report</title>
        <link href="<?php echo CONTEXT_PATH ?>/css/report.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo CONTEXT_PATH ?>/favicon.ico" type="image/ico" />
    </head>
    <body id="report">
        <div class=report>
            <div class="tr">
                <span class="th col-1">Date</span>
                <span class="th col-2">Tickets</span>
                <span class="th col-3">Points</span>
                <div class="clear"></div>
            </div>
            <?php
            $orders = $_GET['orders'];
            for ($i = 0; $i < $orders->count(); $i++) {
                echo "<div class=tr>";
                echo "<span class=\"td col-1\">" . $orders[$i]->getValue('creation_date') . "</span>";
                echo "<span class=\"td col-2\">" . $orders[$i]->getValue('tikects') . "</span>";
                echo "<span class=\"td col-3\">" . number_format($orders[$i]->getValue('points')) . "</span>";
                echo "<div class=clear></div>";
                echo "</div>";
            }
            ?>
        </div>
    </body>
</html>