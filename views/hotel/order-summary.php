<div style="max-height: 400px; width: 310px; overflow:auto">
<h3 style="text-align:right; width: 280px; margin:10px 0 15px 0; line-height: 18px">Points: <span style="text-align:right;width: 120px;display:inline-block"><?php echo $_GET['summary']['point']?></span></h3>
<h3 style="text-align:right; width: 280px; margin:10px 0 15px 0; line-height: 18px">Tickets: <span style="text-align:right;width: 120px;display:inline-block"><?php echo $_GET['summary']['tickets']?></span></h3>

<?php
$orders = $_GET['orders'];
for ($i = 0; $i < $orders->count(); $i++) {
    if ($i < 1 || $orders[$i-1]->getValue('user') != $orders[$i]->getValue('user')) {
        echo ucfirst($orders[$i]->getValue('user'));
        ?><hr style="width: 280px; margin:10px 0 15px 0; line-height: 18px"/><?php
    }
    ?>
    <h3 style="text-align:right; width: 280px; margin:10px 0 15px 0; line-height: 18px">Points:<span style="text-align:right;width: 120px;display:inline-block"><?php echo $orders[$i]->getValue('point')?></span></h3>
    <h3 style="text-align:right; width: 280px; margin:10px 0 15px 0; line-height: 18px">Ticktes: <span style="text-align:right;width: 120px;display:inline-block"><?php echo $orders[$i]->getValue('total')?></span></h3>
    <?php
}
?>
</div>