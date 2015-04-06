<?php
if (isset($_GET['error'])) {
    require 'includes/error.php';
    return;
}
$passwordChangeRequests = $_GET['password-change-requests'];
for ($i = 0; $i < $passwordChangeRequests->count(); $i++) {
    ?>
    <div class="tr notselected" id="<?php echo $passwordChangeRequests[$i]->getValue('id') ?>">
        <span class="td select" id="<?php echo $passwordChangeRequests[$i]->getValue('id') ?>" title="Select"></span>
        <span class="td w80" style="text-align:center"><?php echo $passwordChangeRequests[$i]->getValue('userid') ?></span>
        <span class="td w150"><?php echo $passwordChangeRequests[$i]->getValue('name') ?></span>
        <span class="td w150"><?php echo $passwordChangeRequests[$i]->getValue('password') ?></span>
        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($passwordChangeRequests[$i]->getValue('creation_date')) ?></span>
        <span class="td w80" style="text-align:center"><a href="<?php echo CONTEXT_PATH?>/backend/prepaid/password-change-requests?action=activate&id=<?php echo $passwordChangeRequests[$i]->getValue('id')?>">Activate</a></span>
    </div>
    <?php
}