<div class="trans-status">
    <h3>Your Transaction</h3>
    <div class="content fail">
        <p>Your transaction was not successful</p>
        <p>Reason: <?php echo $_GET['transaction']["ResponseDescription"] ?></p>
        <p>Transaction Reference: <?php echo $_GET['card-order']->getValue('id') ?></p>
        <p><a href="http://hotspotgenesis.com/login">Click here to go back</a></p>
    </div>
</div>
<div class="clear"></div>