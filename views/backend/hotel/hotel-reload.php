<?php
if (isset($_GET['error'])) {
    require 'includes/error.php';
    return;
}
$hotels = $_GET['hotels'];
for ($i = 0; $i < $hotels->count(); $i++) {
    ?>
    <div class="tr notselected" id="<?php echo $hotels[$i]->getValue('id') ?>">
        <span class="td select" id="<?php echo $hotels[$i]->getValue('id') ?>" title="Select"></span>
        <span class="td w150"><?php echo $hotels[$i]->getValue('name') ?></span>
        <span class="td w80" style="text-align:center"><?php echo $hotels[$i]->getValue('phone') ?></span>
        <span class="td w120" style="text-align:right"><?php echo $hotels[$i]->getValue('monthly_amount') ?></span>
        <span class="td w300" title="<?php echo $hotels[$i]->getValue('sms') ?>"><?php echo $hotels[$i]->getValue('sms') ?></span>
        <span class="td w120"><?php echo $hotels[$i]->getValue('sms_sender_name') ?></span>
        <?php
        if ($hotels[$i]->getValue('disabled') == 1) {
            ?><span class="td w80" style="text-align:center">Disable</span><?php
    } else {
            ?><span class="td w80" style="text-align:center">Active</span><?php
    }
        ?>
        <span class="td w200" title="<?php echo $hotels[$i]->getValue('address') ?>"><?php echo $hotels[$i]->getValue('address') ?></span>
        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($hotels[$i]->getValue('creation_date')) ?></span>
        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($hotels[$i]->getValue('last_changed')) ?></span>
    </div>
    <?php
}