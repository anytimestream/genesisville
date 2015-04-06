<?php
if (isset($_GET['error'])) {
    require 'includes/error.php';
    return;
}
$stations = $_GET['stations'];
for ($i = 0; $i < $stations->count(); $i++) {
    ?>
    <div class="tr notselected" id="<?php echo $stations[$i]->getValue('id') ?>">
        <span class="td select" id="<?php echo $stations[$i]->getValue('id') ?>" title="Select"></span>
        <span class="td w150"><?php echo $stations[$i]->getValue('name') ?></span>
        <span class="td w80" style="text-align:center"><?php echo $stations[$i]->getValue('phone') ?></span>
        <span class="td w300" title="<?php echo $stations[$i]->getValue('sms') ?>"><?php echo $stations[$i]->getValue('sms') ?></span>
        <span class="td w120"><?php echo $stations[$i]->getValue('sms_sender_name') ?></span>
        <?php
        if ($stations[$i]->getValue('disabled') == 1) {
            ?><span class="td w80" style="text-align:center">Disable</span><?php
    } else {
            ?><span class="td w80" style="text-align:center">Active</span><?php
    }
        ?>
        <?php
        if ($stations[$i]->getValue('accept_prepaid_order') == 1) {
            ?><span class="td w120" style="text-align:center">Yes</span><?php
    } else {
            ?><span class="td w120" style="text-align:center">No</span><?php
    }
        ?>
        <span class="td w200" title="<?php echo $stations[$i]->getValue('address') ?>"><?php echo $stations[$i]->getValue('address') ?></span>
        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($stations[$i]->getValue('creation_date')) ?></span>
        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($stations[$i]->getValue('last_changed')) ?></span>
    </div>
    <?php
}