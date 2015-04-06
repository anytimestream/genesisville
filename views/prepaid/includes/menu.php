<div class="name"></div>
<dl>
    <center><span class="username">Welcome, <?php echo ucfirst($_GET['name']) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></center><br/>
    <!--<dd><a class="<?php echo getCurrentTab('b', 'home', true) ?>" href="<?php echo CONTEXT_PATH; ?>/prepaid/home">Home</a></dd>-->
    <dd><a class="<?php echo getCurrentTab('b', 'new-subscription', false) ?>" href="<?php echo CONTEXT_PATH; ?>/prepaid">Subscription</a></dd>
    <dd><a class="<?php echo getCurrentTab('b', 'payment-history', false) ?>" href="<?php echo CONTEXT_PATH; ?>/prepaid/payment-history">Payment History</a></dd>
    <dd><a class="<?php echo getCurrentTab('b', 'personal-details', false) ?>" href="<?php echo CONTEXT_PATH; ?>/prepaid/personal-details">Personal Details</a></dd>
    <dd><a class="<?php echo getCurrentTab('b', 'change-password', false) ?>" href="<?php echo CONTEXT_PATH . '/change-password' ?>">Change Password</a></dd>
    <dd><a href="<?php echo CONTEXT_PATH . '/prepaid-login' ?>">Logout</a></dd>
</dl>