<?php
if(isset($_GET['error'])){
    require 'includes/error.php';
    return;
}
$phones = $_GET['phones'];
for ($i = 0; $i < $phones->count(); $i++) {
    ?>
    <div class="tr notselected" id="<?php echo $phones[$i]->getValue('id')?>">
    <span class="td select" id="<?php echo $phones[$i]->getValue('id')?>" title="Select"></span>
    <span class="td w150"><?php echo $phones[$i]->getValue('phone')?></span>
    <span class="td w150" style="text-align:center"><?php echo $phones[$i]->getValue('revision')?></span>
    <span class="td w150" style="text-align:center"><?php echo $phones[$i]->getValue('type')?></span>
    <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($phones[$i]->getValue('creation_date')) ?></span>
    <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($phones[$i]->getValue('last_changed')) ?></span>
    </div>
    <?php
}