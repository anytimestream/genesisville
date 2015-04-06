<?php
if(isset($_GET['error'])){
    require 'includes/error.php';
    return;
}
$remittance = $_GET['remittance'];
for ($i = 0; $i < $remittance->count(); $i++) {
    ?>
    <div class="tr notselected" id="<?php echo $remittance[$i]->getValue('id')?>">
    <span class="td select" id="<?php echo $remittance[$i]->getValue('id')?>" title="Select"></span>
    <span class="td w150"><?php echo $remittance[$i]->getValue('station')?></span>
    <span class="td w100" style="text-align:right"><?php echo number_format($remittance[$i]->getValue('amount'), 2)?></span>
    <span class="td w100" style="text-align:center"><?php echo Util::AddLeadingZeros($remittance[$i]->getValue('transaction_id'), 9)?></span>
    <?php
    if($remittance[$i]->getValue('status') == 1){
        ?><span class="td w100" style="text-align:center">Approved</span><?php
    }
    else{
        ?><span class="td w100" style="text-align:center">Cancelled</span><?php
    }
    ?>
    <span class="td w100" style="text-align:center"><?php echo $remittance[$i]->getValue('method')?></span>
    <span class="td w150"><?php echo $remittance[$i]->getValue('remarks')?></span>
    <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($remittance[$i]->getValue('creation_date')) ?></span>
    <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($remittance[$i]->getValue('last_changed')) ?></span>
    </div>
    <?php
}