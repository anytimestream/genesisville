<div class="trans-status">
    <h3>Make Payment</h3>
    <div class="content">
        <p>Transaction Reference: <?php echo $_GET['order']->getValue('transaction_id') ?></p>
        <p>You will need your transaction reference if you have any problem with your payment</p>
    </div>
</div>
<div class="clear"></div>
<form action="<?php echo GTPAY_TRANS_REQUEST ?>" method="post">
    <input type='hidden' name='gtpay_mert_id' value='1410' />
    <input type='hidden' name='gtpay_tranx_curr' value='566' />
    <input type='hidden' name='gtpay_cust_name' value='Service Client' />
    <input type='hidden' name='gtpay_gway_name' value='webpay' />
    <input type='hidden' name='gtpay_gway_first' value='yes' />
    <input type='hidden' name='gtpay_tranx_hash' value='<?php echo hash("sha512", $_GET['order']->getValue('transaction_id') . bcmul($_GET['order']->getValue('amount'), 100) . CONTEXT_PATH . '/hotspot/notify2' . GTPAY_HASH_KEY); ?>' />
    <input type='hidden' name='gtpay_tranx_id' value='<?php echo $_GET['order']->getValue('transaction_id') ?>' />
    <input type='hidden' name='gtpay_cust_id' value='<?php echo $_GET['user'] ?>' />
    <input type='hidden' name='gtpay_tranx_noti_url' value='<?php echo CONTEXT_PATH . '/hotspot/notify2' ?>' />
    <input type='hidden' name='gtpay_tranx_memo' value='Subscription to GenesisVille Hotspot Service' />
    <input type='hidden' name='gtpay_tranx_amt' value='<?php echo bcmul($_GET['order']->getValue('amount'), 100) ?>' />
    <div class=row>
        <center><button style="margin-left: 5px;">Proceed</button></center>
    </div>
</form>