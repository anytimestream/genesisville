<?php
if(isset($_GET['error'])){
    require 'includes/error.php';
    return;
}
$plans = $_GET['plans'];
for ($i = 0; $i < $plans->count(); $i++) {
    ?>
    <div class="tr notselected" id="<?php echo $plans[$i]->getValue('id')?>">
    <span class="td select" id="<?php echo $plans[$i]->getValue('id')?>" title="Select"></span>
    <span class="td w150"><?php echo $plans[$i]->getValue('name')?></span>
    <span class="td w120" style="text-align:right"><?php echo $plans[$i]->getValue('amount')?></span>
    <span class="td w200"><?php echo $plans[$i]->getValue('description')?></span>
    <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($plans[$i]->getValue('creation_date')) ?></span>
    <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($plans[$i]->getValue('last_changed')) ?></span>
    </div>
    <?php
}