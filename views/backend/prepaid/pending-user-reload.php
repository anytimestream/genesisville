<?php
if (isset($_GET['error'])) {
    require 'includes/error.php';
    return;
}
$users = $_GET['users'];
for ($i = 0; $i < $users->count(); $i++) {
    $info = PrepaidUserService::GetCurrentPendingInfo($users[$i]->getValue('id'));
    ?>
    <div class="tr notselected" id="<?php echo $users[$i]->getValue('id') ?>">
        <span class="td select" id="<?php echo $users[$i]->getValue('id') ?>" title="Select"></span>
        <span class="td w150"><?php echo $users[$i]->getValue('name') ?></span>
        <span class="td w120"><?php echo $info['plan'] ?></span>
        <span class="td w80" style="text-align:center;"><?php echo Date::convertFromMySqlDate($info['expires']) ?></span>
        <span class="td w80"><?php echo $users[$i]->getValue('phone') ?></span>
        <span class="td w150"><?php echo $users[$i]->getValue('email') ?></span>
        <span class="td w120"><?php echo $users[$i]->getValue('agent') ?></span>
        <span class="td w180"><?php echo $users[$i]->getValue('address') ?></span>
        <span class="td w80"><?php echo $users[$i]->getValue('city') ?></span>
        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($users[$i]->getValue('creation_date')) ?></span>
        <span class="td w80" style="text-align:center"><a href="<?php echo CONTEXT_PATH?>/backend/prepaid/pending-users?action=accept&id=<?php echo $users[$i]->getValue('id')?>">Accept</a></span>
    </div>
    <?php
}