<?php
if (isset($_GET['error'])) {
    require 'includes/error.php';
    return;
}
$orders = $_GET['orders'];
for ($i = 0; $i < $orders->count(); $i++) {
    ?>
    <div class="tr notselected" id="<?php echo $orders[$i]->getValue('id') ?>">
        <span class="td select" id="<?php echo $orders[$i]->getValue('id') ?>" title="Select"></span>
        <span class="td w120"><?php echo $orders[$i]->getValue('id') ?></span>
        <span class="td w120"><?php echo $orders[$i]->getValue('plan') ?></span>
        <span class="td w80" style="text-align:center"><?php echo $orders[$i]->getValue('phone') ?></span>
        <span class="td w150"><?php echo $orders[$i]->getValue('email') ?></span>
        <span class="td w100" style="text-align:center"><?php echo $orders[$i]->getValue('response_code') ?></span>
        <span class="td w150" title="<?php echo $orders[$i]->getValue('response_description') ?>"><?php echo $orders[$i]->getValue('response_description') ?></span>
        <span class="td w120" style="text-align:center"><?php echo $orders[$i]->getValue('merchant_reference') ?></span>
        <?php
        if ($orders[$i]->getValue('status') == 0) {
            ?><span class="td w100" style="text-align:center">Pending</span><?php
    } else if ($orders[$i]->getValue('status') == 1) {
            ?><span class="td w100" style="text-align:center">Disputed</span><?php
    } else if ($orders[$i]->getValue('status') == 2) {
            ?><span class="td w100" style="text-align:center">Failed</span><?php
    }
        ?>
        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($orders[$i]->getValue('creation_date')) ?></span>
        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($orders[$i]->getValue('last_changed')) ?></span>
    </div>
    <?php
}