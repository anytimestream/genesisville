<form class="normosa-ui-form prepaid_user" method="post" action="<?php echo CONTEXT_PATH ?>/backend/prepaid/orders?action=insert" style="height: 400px; overflow: auto; width: 450px;">
    <div class="row">
        <label class="label" for="user">User</label>
        <select inputtype="_default" id="user" name="user" class="textbox w200 h23" onchange="prepaid_user_form('user')">
            <option value="">New User</option>
            <?php
            $users = $_GET['users'];
            for ($i = 0; $i < $users->count(); $i++) {
                ?>
                <option value="<?php echo $users[$i]->getValue('id') ?>"><?php echo $users[$i]->getValue('user_id') ?> - <?php echo $users[$i]->getValue('name') ?></option>
                <?php
            }
            ?>
        </select>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div id="prepaid_new_user">
        <div class="row">
            <label class="label" for="name">Name</label>
            <input inputtype="textbox" id="name" name="name" class="textbox w200" validation="String" min="2" maxlength=30"/>
            <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
        </div>
        <div class="row">
            <label class="label" for="password">Password</label>
            <input inputtype="textbox" id="password" name="password" class="textbox w200" validation="String" min="4" maxlength="15"/>
            <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
        </div>
        <div class="row">
            <label class="label" for="phone">Phone</label>
            <input inputtype="textbox" id="phone" name="phone" class="textbox w200" validation="String" min="11" maxlength="11"/>
            <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
        </div>
        <div class="row">
            <label class="label" for="email">Email</label>
            <input inputtype="textbox" id="email" name="email" class="textbox w200" validation="Email" allownull="true" maxlength="200"/>
            <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
        </div>
        <div class="row">
            <label class="label" for="address">Address</label>
            <input inputtype="textbox" id="address" name="address" class="textbox w200" validation="String" min="2" maxlength="300"/>
            <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
        </div>
        <div class="row">
            <label class="label" for="city">City</label>
            <input inputtype="textbox" id="city" name="city" class="textbox w200" validation="String" min="2" maxlength="20" value="Warri"/>
            <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
        </div>
    </div>
    <div class="row">
        <label class="label" for="plan">Plan</label>
        <select inputtype="_default" id="plan" name="plan" class="textbox w200 h23" onchange="prepaid_user_form('')">
            <?php
            $plans = $_GET['plans'];
            for ($i = 0; $i < $plans->count(); $i++) {
                ?>
                <option value="<?php echo $plans[$i]->getValue('name') ?>"><?php echo $plans[$i]->getValue('name') ?> - N<?php echo number_format($plans[$i]->getValue('amount'), 2) ?></option>
                <?php
            }
            ?>
        </select>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="tenure">Duration</label>
        <select inputtype="_default" id="tenure" name="tenure" class="textbox w200 h23" onchange="prepaid_user_form('')">
            <option value="1">1 Month (30 days)</option>
            <option value="2">2 Months (60 days)</option>
            <option value="3">3 Months (90 days)</option>
            <option value="4">4 Months (120 days)</option>
            <option value="5">5 Months (150 days)</option>
            <option value="6">6 Months (180 days)</option>
            <option value="7">7 Months (210 days)</option>
            <option value="8">8 Months (240 days)</option>
            <option value="9">9 Months (270 days)</option>
            <option value="10">10 Months (300 days)</option>
            <option value="11">11 Months (330 days)</option>
            <option value="12">12 Months (360 days)</option>
        </select>
    </div>
    <div class="row">
        <label class="label" for="amount">Amount</label>
        <input inputtype="_default" disabled id="amount" name="amount" class="textbox w200" value="<?php echo number_format($_GET['plans'][0]->getValue('amount'), 2) ?>"/>
    </div>
    <div class="row">
        <label class="label" for="start">Start</label>
        <input inputtype="date" id="start" name="start" class="textbox w200" value="<?php echo date('d/m/Y') ?>"/>
    </div>
    <div class="row">
        <label class="label" for="agent">Agent</label>
        <select inputtype="_default" id="agent" name="agent" class="textbox w200 h23">
            <?php
            $agents = $_GET['agents'];
            for ($i = 0; $i < $agents->count(); $i++) {
                ?>
                <option><?php echo $agents[$i]->getValue('name') ?></option>
                <?php
            }
            ?>
        </select>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="method">Payment Method</label>
        <select inputtype="_default" id="method" name="method" class="textbox w200 h23">
            <option>Bank</option>
            <option>Cash</option>
        </select>
    </div>
    <div class="row">
        <label class="label" for="remarks">Remarks</label>
        <input inputtype="textbox" id="remarks" name="remarks" class="textbox w200" validation="String" maxlength="300"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <center>
            <span id="wait" style="display: none">Processing...</span>
            <button id="submit">Create</button>
        </center>
    </div>
</form>