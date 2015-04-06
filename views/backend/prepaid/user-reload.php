<?php
if (isset($_GET['error'])) {
    require 'includes/error.php';
    return;
}
$users = $_GET['users'];
for ($i = 0; $i < $users->count(); $i++) {
    $info = PrepaidUserService::GetCurrentInfo($users[$i]->getValue('id'));
    $bg = '#fff';
    $color = '#333';
    $expires = strtotime($info['expires']);
    $now = strtotime(date('Y-m-d'));
    if ($now > $expires) {
        $bg = '#c02';
        $color = '#fff';
    } else {
        $diff = abs($expires - $now);
        $days = floor($diff / (60 * 60 * 24));
        if ($days < 8) {
            $bg = 'yellow';
            $color = '#333';
        }
    }
    ?>
    <div class="tr notselected" id="<?php echo $users[$i]->getValue('id') ?>">
        <span class="td select" id="<?php echo $users[$i]->getValue('id') ?>" title="Select"></span>
        <span class="td w50"><?php echo $users[$i]->getValue('username') ?></span>
        <span class="td w120"><?php echo $info['plan'] ?></span>
        <span class="td w80" style="text-align:center; background: <?php echo $bg ?>; color: <?php echo $color ?>"><?php echo Date::convertFromMySqlDate($info['expires']) ?></span>
        <span class="td w80"><?php echo $users[$i]->getValue('phone') ?></span>
        <span class="td w150"><?php echo $users[$i]->getValue('email') ?></span>
        <span class="td w150"><?php echo $users[$i]->getValue('name') ?></span>
        <span class="td w120"><?php echo $users[$i]->getValue('agent') ?></span>
        <span class="td w180"><?php echo $users[$i]->getValue('address') ?></span>
        <span class="td w80"><?php echo $users[$i]->getValue('city') ?></span>
        <?php
        if ($users[$i]->getValue('state') == 1) {
            ?><span class="td w80" style="text-align:center">Activated</span><?php
    } else {
            ?><span class="td w80" style="text-align:center">Pending</span><?php
    }
        ?>
        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($users[$i]->getValue('creation_date')) ?></span>
        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($users[$i]->getValue('last_changed')) ?></span>
    </div>
    <?php
}