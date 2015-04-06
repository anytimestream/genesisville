<div style="max-height: 400px; width: 310px; overflow:auto">
<h3 style="text-align:right; width: 280px; margin:10px 0 15px 0; line-height: 18px">Total Amount:<span style="text-align:right;width: 120px;display:inline-block">N<?php  echo number_format($_GET['summary']['amount'], 2)?></span></h3>
<h3 style="text-align:right; width: 280px; margin:10px 0 15px 0; line-height: 18px">Contract Value: <span style="text-align:right;width: 120px;display:inline-block">N<?php echo number_format($_GET['summary']['contract_value'], 2)?></span></h3>
<h3 style="text-align:right; width: 280px; margin:10px 0 15px 0; line-height: 18px">Tickets: <span style="text-align:right;width: 120px;display:inline-block"><?php echo $_GET['summary']['tickets']?></span></h3>

<?php
$orders = $_GET['orders'];
for ($i = 0; $i < $orders->count(); $i++) {
    if ($i < 1 || $orders[$i-1]->getValue('user') != $orders[$i]->getValue('user')) {
        echo ucfirst($orders[$i]->getValue('user'));
        ?><hr style="width: 280px; margin:10px 0 15px 0; line-height: 18px"/><?php
    }
    ?>
    <h3 style="text-align:right; width: 280px; margin:10px 0 15px 0; line-height: 18px">Sales:<span style="text-align:right;width: 120px;display:inline-block">N<?php echo number_format($orders[$i]->getValue('amount'), 2)?></span></h3>
    <h3 style="text-align:right; width: 280px; margin:10px 0 15px 0; line-height: 18px">Ticktes: <span style="text-align:right;width: 120px;display:inline-block"><?php echo $orders[$i]->getValue('total')?></span></h3>
    <?php
}
?>
</div>