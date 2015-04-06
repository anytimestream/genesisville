<br/><br/><br/><br/><br/><br/>
<form class="normosa-ui-form" method="post" action="">
    <div class="row">
        <label class="label" for="plan">Plan</label>
        <select inputtype="_default" id="plan" name="plan" class="textbox w200 h23">
            <?php
            $plans = $_GET['plans'];
            for ($i = 0; $i < $plans->count(); $i++) {
                if ($_GET['plan'] == $plans[$i]->getValue('name')) {
                    ?>
                    <option selected value="<?php echo $plans[$i]->getValue('name') ?>"><?php echo $plans[$i]->getValue('name') ?> - N<?php echo number_format($plans[$i]->getValue('amount'), 2) ?></option>
                    <?php
                } else {
                    ?>
                    <option value="<?php echo $plans[$i]->getValue('name') ?>"><?php echo $plans[$i]->getValue('name') ?> - N<?php echo number_format($plans[$i]->getValue('amount'), 2) ?></option>
                    <?php
                }
            }
            ?>
        </select>
        <span class="error" style="display: block;padding-left: 110px; color: #00ff00;font-size: 11px;"></span>
    </div>
    <div class="row">
        <label class="label" for="tenure">Duration</label>
        <select inputtype="_default" id="tenure" name="tenure" class="textbox w200 h23">
            <option value="1">1 Month (30 days)</option>
            <option value="2">2 Months (60 days)</option>
            <option value="3">3 Months (90 days)</option>
            <option value="4">4 Months (120 days)</option>
            <option value="5">5 Months (150 days)</option>
            <option value="6">6 Months (180 days)</option>
            <option value="7">7 Months (110 days)</option>
            <option value="8">8 Months (240 days)</option>
            <option value="9">9 Months (270 days)</option>
            <option value="10">10 Months (300 days)</option>
            <option value="11">11 Months (330 days)</option>
            <option value="12">12 Months (360 days)</option>
        </select>
    </div>
    <div class="row">
        <label class="label" for="method">Payment</label>
        <select inputtype="_default" id="method" name="method" class="textbox w200 h23">
            <option value="1">Online Payment (ATM)</option>
            <option value="2">Bank</option>
        </select>
    </div>
    <div class="row" style="width: 300px">
        <center><button>Subscribe</button></center>
    </div>
</form>