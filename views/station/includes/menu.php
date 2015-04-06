<div class="name"></div>
<dl>
    <center><span class="username">Welcome, <?php echo ucfirst(UserService::GetUser()->getValue('username')) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></center><br/>
    <dd><a class="<?php echo getCurrentTab('b', 'tickets', true) ?>" href="<?php echo CONTEXT_PATH; ?>/station/tickets">Tickets</a></dd>
    <dd><a class="<?php echo getCurrentTab('b', 'orders', false) ?>" href="<?php echo CONTEXT_PATH; ?>/station/orders">Orders</a></dd>
    <dd><a class="<?php echo getCurrentTab('b', 'report', false) ?>" href="<?php echo CONTEXT_PATH; ?>/station/report">Report</a></dd>
    <?php
    if(StationService::CanAcceptPrepaid()){
        ?><dd><a class="<?php echo getCurrentTab('b', 'prepaid-orders', false) ?>" href="<?php echo CONTEXT_PATH; ?>/station/prepaid-orders">Prepaid Orders</a></dd><?php
    }
    ?>
    <dd><a class="<?php echo getCurrentTab('b', 'change-password', false) ?>" href="<?php echo CONTEXT_PATH . '/change-password' ?>">Change Password</a></dd>
    <dd><a href="<?php echo CONTEXT_PATH . '/login' ?>">Logout</a></dd>
</dl>
<span class="username" style="text-align: center;">Account Balance</span>
<h2 style="text-align: center;">
    <?php
    $balance = $_GET['balance'];
    if ($balance < 0) {
        echo "<span style=\"color: red\">" . number_format($balance, 2) . "</span>";
    } else {
        echo "<span style=\"color: #00ff00\">" . number_format($balance, 2) . "</span>";
    }
    ?>
</h2>