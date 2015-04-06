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
        <span class="td w120"><?php echo $orders[$i]->getValue('plan') ?></span>
        <?php
        if ($orders[$i]->getValue('state') == 1) {
            ?><span class="td w80" style="text-align:center">Activated</span><?php
    } else {
            ?><span class="td w80" style="text-align:center">Pending</span><?php
    }
        ?>
        <span class="td w80" style="text-align:right"><?php echo number_format($orders[$i]->getValue('amount'), 2) ?></span>
        <span class="td w80" style="text-align:center"><?php echo (30 * $orders[$i]->getValue('tenure')) ?> Days</span>
        <span class="td w80" style="text-align:center"><?php echo Date::convertFromMySqlDate($orders[$i]->getValue('expires')) ?></span>
        <span class="td w80" style="text-align:center"><?php echo Util::AddLeadingZeros($orders[$i]->getValue('transaction_id'), 9) ?></span>
        <span class="td w120"><?php echo $orders[$i]->getValue('remarks') ?></span>
        <span class="td w100" style="text-align:center"><?php echo $orders[$i]->getValue('method') ?></span>
        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($orders[$i]->getValue('creation_date')) ?></span>
    </div>
    <?php
}