<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Print Ticket</title>
        <link href="<?php echo CONTEXT_PATH ?>/ticket.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="favicon.ico" type="image/ico" />
    </head>
    <body id="ticket">
        <div class=ticket>
            <div class="name"><?php  ?></div>
            <div class="address"><?php ?></div>
            <div class="address"><?php echo substr(Date::convertFromMySqlDate($_GET['order']->getValue('creation_date')), 0, 10) . ' &nbsp;&nbsp;(' . Util::AddLeadingZeros($_GET['order']->getValue('transaction_id'), 9) . ')' ?></div><br/>
            <span class="col-1 lh-22">Plan:</span><span class="col-2"><b><?php echo $_GET['order']->getValue('plan') ?></b></span><br/>
            <span class="col-1 lh-22">Username:</span><span class="col-2"><b><?php echo $_GET['order']->getValue('account_number') ?></b></span><br/>
            <span class="col-1 lh-16">Amount:</span><span class="col-2 lh-16">N<?php echo number_format($_GET['order']->getValue('amount'), 2) ?></span><br/>  
            <span class="col-1 lh-16">Duration:</span><span class="col-2 lh-16"><?php echo ($_GET['order']->getValue('tenure') * 30) ?> Days</span><br/>
            <?php
            $expire_parts = explode('-', $_GET['order']->getValue('expires'));
            $expire = mktime(0, 0, 0, $expire_parts[1], $expire_parts[2], $expire_parts[0]);
            $begin = $expire - (60 * 60 * 24 * 30 * $_GET['order']->getValue('tenure'));
            ?>
            <span class="col-1 lh-16">Start Time:</span><span class="col-2 lh-16"><?php echo date('d/m/Y', $begin) ?></span><br/>
            <span class="col-1 lh-16">End Time:</span><span class="col-2 lh-16"><?php echo Date::convertFromMySqlDate($_GET['order']->getValue('expires')) ?></span><br/>
        </div>
    </body>
</html>