<div class="name"></div>
<dl>
    <center><span class="username">Welcome, <?php echo ucfirst(UserService::GetUser()->getValue('username')) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></center><br/>
    <dd><a class="<?php echo getCurrentTab('b', 'tickets', true) ?>" href="<?php echo CONTEXT_PATH; ?>/hotel/tickets">Tickets</a></dd>
    <dd><a class="<?php echo getCurrentTab('b', 'orders', false) ?>" href="<?php echo CONTEXT_PATH; ?>/hotel/orders">Orders</a></dd>
    <dd><a class="<?php echo getCurrentTab('b', 'report', false) ?>" href="<?php echo CONTEXT_PATH; ?>/hotel/report">Report</a></dd>
    <dd><a class="<?php echo getCurrentTab('b', 'change-password', false) ?>" href="<?php echo CONTEXT_PATH . '/change-password' ?>">Change Password</a></dd>
    <dd><a href="<?php echo CONTEXT_PATH . '/login' ?>">Logout</a></dd>
</dl>
<span class="username" style="text-align: center;">Used Points</span>
<h2 style="text-align: center;">
    <?php
    echo $_GET['points'];
    ?>
</h2>