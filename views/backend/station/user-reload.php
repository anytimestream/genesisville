<?php
if(isset($_GET['error'])){
    require 'includes/error.php';
    return;
}
$users = $_GET['users'];
for ($i = 0; $i < $users->count(); $i++) {
    ?>
    <div class="tr notselected" id="<?php echo $users[$i]->getValue('id')?>">
    <span class="td select" id="<?php echo $users[$i]->getValue('id')?>" title="Select"></span>
    <span class="td w150"><?php echo $users[$i]->getValue('station')?></span>
    <span class="td w150"><?php echo ucfirst($users[$i]->getValue('user'))?></span>
    <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($users[$i]->getValue('creation_date')) ?></span>
    <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($users[$i]->getValue('last_changed')) ?></span>
    </div>
    <?php
}