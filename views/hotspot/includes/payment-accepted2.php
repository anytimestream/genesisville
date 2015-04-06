<div class="trans-status">
    <h3>Your Transaction</h3>
    <div class="content">
        <p>Approved Successful</p>
        <p>Your Hotspot Ticket has been sent to your Phone (<?php echo $_GET['card-order']->getValue('phone') ?>)</p>
        <p><a href="http://hotspotgenesis.com/login">Click here to login</a></p>
        <p>Transaction Reference: <?php echo $_GET['card-order']->getValue('id') ?></p>
    </div>
</div>
<div class="clear"></div>