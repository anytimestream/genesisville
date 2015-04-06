<?php
if(isset($_GET['error'])){
    require 'includes/error.php';
    return;
}
$gateways = $_GET['gateways'];
for ($i = 0; $i < $gateways->count(); $i++) {
    ?>
    <div class="tr notselected" id="<?php echo $gateways[$i]->getValue('id')?>">
    <span class="td select" id="<?php echo $gateways[$i]->getValue('id')?>" title="Select"></span>
    <span class="td w150"><?php echo $gateways[$i]->getValue('name')?></span>
    <?php
    if($gateways[$i]->getValue('isDefault') == 1){
        ?><span class="td w100">Yes</span><?php
    }
    else{
        ?><span class="td w100">No</span><?php
    }
    ?>
    </div>
    <?php
}