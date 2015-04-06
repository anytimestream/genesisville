<script type="text/javascript" src="<?php echo CONTEXT_PATH ?>/js/ext/jquery-1.7.1.min.js" ></script>
<script language="javascript" type="text/javascript">
    $(document).ready(function(){
        var buy = $('#buy_now');
        if(buy.length > 0){
            buy[0].submit();
        }
    });
</script>
<center><span style="float: left; width: 60px;line-height: 40px;height: 40px;">Processing</span><span class="processing"></span></center>
<form id="buy_now" action="https://voguepay.com/pay/" method="post" style="display: none;">
    <input type='hidden' name='v_merchant_id' value='12263-3603' />
    <input type='hidden' name='merchant_ref' value='<?php echo $_GET['card-order']->getValue('id') ?>' />
    <input type='hidden' name='notify_url' value='<?php echo CONTEXT_PATH . '/hotspot/notify' ?>' />
    <input type='hidden' name='item_1' value='<?php echo $_GET['plan']->getValue('description') ?>' />
    <input type='hidden' name='description_1' value='Subscription to GenesisVille Hotspot Service' />
    <input type='hidden' name='price_1' value='<?php echo $_GET['plan']->getValue('amount') ?>' />
    <input type='hidden' name='total' value='<?php echo $_GET['plan']->getValue('amount') ?>' />
    <input type='image' src='http://voguepay.com/images/buttons/buynow_blue.png' alt='Submit' />
</form>