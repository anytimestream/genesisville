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
                <span class="th col-3">Amount</span>
                <span class="th col-4">Remit</span>
                <div class="clear"></div>
            </div>
            <?php
            $start = $_GET['start'];
            $end = $_GET['end'];
            while ($start < $end) {
                $start += 24 * 60 * 60;
                echo "<div class=tr>";
                echo "<span class=\"td col-1\">" . date('d/m/Y', $start) . "</span>";
                $report = StationService::GetReportByDate(date('Y-m-d', $start));
                if ($report['tickets'] == 0) {
                    echo "<span class=\"td col-2\">-</span>";
                    echo "<span class=\"td col-3\">-</span>";
                } else {
                    echo "<span class=\"td col-2\">" . $report['tickets'] . "</span>";
                    echo "<span class=\"td col-3\">N" . number_format($report['amount'], 2) . "</span>";
                }
                $remit = StationService::GetRemittanceByDate(date('Y-m-d', $start));
                if ($remit == 0) {
                    echo "<span class=\"td col-4\">-</span>";
                } else {
                    echo "<span class=\"td col-4\">N" . number_format($remit, 2) . "</span>";
                }
                echo "<div class=clear></div>";
                echo "</div>";
            }
            ?>
        </div>
        <div class="summary">
            <b>Tickets:</b> <span><?php echo StationService::GetTotalTicketsReport(); ?></span><br/>
            <b>Amount:</b> <span>N<?php echo number_format(StationService::GetTotalAmountReport(), 2) ?></span><br/>
            <b>Remit:</b> <span>N<?php echo number_format(StationService::GetTotalRemitReport(), 2) ?></span><br/><hr/>
            <b>Cumulative Tickets:</b> <span><?php echo $_GET['orders2'][0]->getValue('total') ?></span><br/>
            <b>Cumulative Amount:</b><span><?php echo number_format($_GET['orders2'][0]->getValue('amount'), 2) ?></span><br/>
            <b>Cumulative Remit:</b><span><?php echo number_format($_GET['payments2'][0]->getValue('amount'), 2) ?> </span><br/><hr/>
            <b>Account Balance:</b><span><?php echo number_format($_GET['payments2'][0]->getValue('amount') - $_GET['orders2'][0]->getValue('amount'), 2) ?> </span><br/>
        </div>
    </body>
</html>