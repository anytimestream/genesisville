<div class="trans-status">
    <h3>Your Transaction</h3>
    <div class="content fail">
        <p>Your transaction was not successful</p>
        <p>Reason: <?php echo $_GET['transaction']["ResponseDescription"] ?></p>
        <p>Transaction Reference: <?php echo $_GET['order']->getValue('transaction_id') ?></p>
    </div>
</div>
<div class="clear"></div>