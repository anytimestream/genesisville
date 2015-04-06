<form class="normosa-ui-form" method="post" action="<?php echo CONTEXT_PATH ?>/backend/station/remittance?action=insert">
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
        <label class="label" for="amount">Amount</label>
        <input inputtype="textbox" id="amount" name="amount" class="textbox w200" validation="Number" min="1"/>
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
        <center><button>Create</button></center>
    </div>
</form>