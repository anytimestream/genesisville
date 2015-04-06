<?php
if(isset($_GET['error'])){
    require 'includes/error.php';
    return;
}
$agents = $_GET['agents'];
for ($i = 0; $i < $agents->count(); $i++) {
    ?>
    <div class="tr notselected" id="<?php echo $agents[$i]->getValue('id')?>">
    <span class="td select" id="<?php echo $agents[$i]->getValue('id')?>" title="Select"></span>
    <span class="td w150"><?php echo $agents[$i]->getValue('name')?></span>
    <span class="td w150"><?php echo $agents[$i]->getValue('phone')?></span>
    <span class="td w150"><?php echo $agents[$i]->getValue('email')?></span>
    <span class="td w100" style="text-align:center"><?php echo AgentService::GetNoOfUsers($agents[$i]->getValue('name'))?></span>
    <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($agents[$i]->getValue('creation_date')) ?></span>
    <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($agents[$i]->getValue('last_changed')) ?></span>
    </div>
    <?php
}