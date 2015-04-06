<?php
if(isset($_GET['error'])){
    require 'includes/error.php';
    return;
}
$orders = $_GET['orders'];
for ($i = 0; $i < $orders->count(); $i++) {
    ?>
    <div class="tr notselected" id="<?php echo $orders[$i]->getValue('id')?>">
    <span class="td select" id="<?php echo $orders[$i]->getValue('id')?>" title="Select"></span>
    <span class="td w150" style="text-align:center"><?php echo $orders[$i]->getValue('name')?></span>
    <span class="td w120"><?php echo $orders[$i]->getValue('plan')?></span>
    <span class="td w80" style="text-align:center"><?php echo number_format($orders[$i]->getValue('amount'), 2)?></span>
    <span class="td w80" style="text-align:center"><?php echo (30 * $orders[$i]->getValue('tenure'))?> Days</span>
    <span class="td w80" style="text-align:center"><?php echo Date::convertFromMySqlDate($orders[$i]->getValue('expires'))?></span>
    <?php
    if($orders[$i]->getValue('status') == 0){
        ?><span class="td w80">Pending</span><?php
    }
    else if($orders[$i]->getValue('status') == 1){
        ?><span class="td w80">Disputed</span><?php
    }
    else if($orders[$i]->getValue('status') == 2){
        ?><span class="td w80">Failed</span><?php
    }
    ?>
    <span class="td w100" style="text-align:center"><?php echo $orders[$i]->getValue('method')?></span>
    <span class="td w120" style="text-align:center"><?php echo $orders[$i]->getValue('gateway_ref')?></span>
    <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($orders[$i]->getValue('creation_date')) ?></span>
    <span class="td w80" style="text-align:center"><a href="<?php echo CONTEXT_PATH?>/backend/prepaid/pending-orders?action=accept&id=<?php echo $orders[$i]->getValue('id')?>">Accept</a></span>
    </div>
    <?php
}