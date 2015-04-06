<form class="normosa-ui-form" method="post" action="<?php echo CONTEXT_PATH ?>/backend/hotel/plans?action=insert">
    <div class="row">
        <label class="label" for="hotel">Hotel</label>
        <select inputtype="_default" id="hotel" name="hotel" class="textbox w200 h23">
            <?php
            $hotels = $_GET['hotels'];
            for ($i = 0; $i < $hotels->count(); $i++) {
                ?>
                <option><?php echo $hotels[$i]->getValue('name') ?></option>
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
        <label class="label" for="point">Point</label>
        <input inputtype="textbox" id="point" name="point" class="textbox w200" validation="Number"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <center><button>Create</button></center>
    </div>
</form>