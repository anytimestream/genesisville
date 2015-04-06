<form class="normosa-ui-form" method="post" action="">
    <div style="float:left; width: 350px;">
        <div class="row">
            <label class="label" for="name">Name</label>
            <input inputtype="textbox" id="name" name="name" class="textbox w200" validation="String" min="2" maxlength=30"/>
            <span class="error" style="display: block;padding-left: 110px; color: #00ff00;font-size: 11px;"></span>
        </div>
        <div class="row">
            <label class="label" for="password">Password</label>
            <input inputtype="textbox" id="password" name="password" class="textbox w200" validation="String" min="4" maxlength="15"/>
            <span class="error" style="display: block;padding-left: 110px; color: #00ff00;font-size: 11px;"></span>
        </div>
        <div class="row">
            <label class="label" for="phone">Phone</label>
            <input inputtype="textbox" id="phone" name="phone" class="textbox w200" validation="String" min="11" maxlength="11"/>
            <span class="error" style="display: block;padding-left: 110px; color: #00ff00;font-size: 11px;"></span>
        </div>
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
            <label class="label" for="method">Payment</label>
            <select inputtype="_default" id="method" name="method" class="textbox w200 h23">
                <option value="1">Online Payment (ATM)</option>
                <option value="2">Bank</option>
            </select>
        </div>
    </div>
    <div style="float:right; width:350px;">
        <div class="row">
            <label class="label" for="email">Email</label>
            <input inputtype="textbox" id="email" name="email" class="textbox w200" validation="Email" allownull="true" maxlength="200"/>
            <span class="error" style="display: block;padding-left: 110px; color: #00ff00;font-size: 11px;"></span>
        </div>
        <div class="row">
            <label class="label" for="address">Address</label>
            <input inputtype="textbox" id="address" name="address" class="textbox w200" validation="String" min="2" maxlength="300"/>
            <span class="error" style="display: block;padding-left: 110px; color: #00ff00;font-size: 11px;"></span>
        </div>
        <div class="row">
            <label class="label" for="city">City</label>
            <input inputtype="textbox" id="city" name="city" class="textbox w200" validation="String" min="2" maxlength="20" value="Warri"/>
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
    </div>
    <div class="clear"></div>
    <div class="row">
        <center><button>Next</button></center>
    </div>
</form>