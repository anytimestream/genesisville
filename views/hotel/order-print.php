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
            <div class="name"><?php echo $_GET['order']->getValue('hotel') ?></div>
            <div class="address"><?php echo $_GET['order']->getValue('address') . ' - ' . $_GET['order']->getValue('phone') ?></div>
            <div class="address"><?php echo substr(Date::convertFromMySqlDate($_GET['order']->getValue('creation_date')), 0, 10) . ' &nbsp;&nbsp;(' . Util::AddLeadingZeros($_GET['order']->getValue('transaction_id'), 9) . ')' ?></div>
            <span class="col-2 h3"><?php echo $_GET['order']->getValue('plan') ?></span><br/>
            <span class="col-1 lh-22">Username:</span><span class="col-2"><b><?php echo $_GET['order']->getValue('username') ?></b></span><br/>
            <span class="col-1 lh-22">Password:</span><span class="col-2"><b><?php echo $_GET['order']->getValue('password') ?></b></span><br/> 
            <span class="col-1 lh-16">Client:</span><span class="col-2 lh-16"><?php echo $_GET['order']->getValue('phone') ?></span><br/>
        </div>
    </body>
</html>