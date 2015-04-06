<?php
if(isset($_GET['error'])){
    require 'includes/error.php';
    return;
}
$cards = $_GET['cards'];
for ($i = 0; $i < $cards->count(); $i++) {
    ?>
    <div class="tr notselected" id="<?php echo $cards[$i]->getValue('id')?>">
    <span class="td select" id="<?php echo $cards[$i]->getValue('id')?>" title="Select"></span>
    <span class="td w120"><?php echo $cards[$i]->getValue('plan')?></span>
    <span class="td w120"><?php echo $cards[$i]->getValue('username')?></span>
    <span class="td w120" style="text-align:center">
        <?php
        if($cards[$i]->getValue('status') == 1){
            ?>Sold<?php
        }
        else{
            ?>Available<?php
        }
        ?>
    </span>
    <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($cards[$i]->getValue('creation_date')) ?></span>
    <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($cards[$i]->getValue('last_changed')) ?></span>
    </div>
    <?php
}