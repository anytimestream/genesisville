<div class="tab">
    <h3>User ID</h3>
    <span><?php echo UserService::GetUser()->getValue('username') ?></span>
</div>
<div class="tab">
    <h3>Current Plan</h3>
    <span><?php echo $_GET['info']['plan'] ?></span>
</div>
<div class="tab">
    <h3>Expiry Date</h3>
    <span><?php echo Date::convertFromMySqlDate($_GET['info']['expires']) ?></span>
</div>
<div class="clear"></div>
<p class="choose-plan">Click a PLAN to Subscribe</p>
<div class="plan">
    <?php
    $plans = $_GET['plans'];
    for ($i = 0; $i < $plans->count(); $i++) {
        ?>
        <a href="<?php echo CONTEXT_PATH . '/prepaid/new-subscription?plan=' . $plans[$i]->getValue('name') ?>">
            <h3><?php echo $plans[$i]->getValue('name') ?></h3>
            <h4>N<?php echo number_format($plans[$i]->getValue('amount'), 2) ?></h4>
        </a>    
        <?php
    }
    ?>
    <div class="clear"></div>
</div>
