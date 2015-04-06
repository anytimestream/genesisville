<form class="normosa-ui-form" method="post" action="<?php echo CONTEXT_PATH ?>/backend/station/plans?action=insert">
    <div class="row">
        <label class="label" for="station">Station</label>
        <select inputtype="_default" id="station" name="station" class="textbox w200 h23">
            <?php
            $stations = $_GET['stations'];
            for ($i = 0; $i < $stations->count(); $i++) {
                ?>
                <option><?php echo $stations[$i]->getValue('name') ?></option>
                <?php
            }
            ?>
        </select>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="plan">Plan</label>
        <select inputtype="_default" id="plan" name="plan" class="textbox w200 h23">
            <?php
            $plans = $_GET['plans'];
            for ($i = 0; $i < $plans->count(); $i++) {
                ?>
                <option value="<?php echo $plans[$i]->getValue('name') ?>"><?php echo $plans[$i]->getValue('name') ?> - N<?php echo $plans[$i]->getValue('amount') ?></option>
                <?php
            }
            ?>
        </select>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="contract_value">Contract Value</label>
        <input inputtype="textbox" id="contract_value" name="contract_value" class="textbox w200" validation="Number"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <center><button>Create</button></center>
    </div>
</form>