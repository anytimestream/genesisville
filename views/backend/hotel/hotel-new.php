<form class="normosa-ui-form" method="post" action="<?php echo CONTEXT_PATH ?>/backend/hotel/hotels?action=insert">
    <div class="row">
        <label class="label" for="name">Name</label>
        <input inputtype="textbox" id="name" name="name" class="textbox w200" validation="String" min="2" maxlength="30"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="monthly_amount">Monthly Amount</label>
        <input inputtype="textbox" id="monthly_amount" name="monthly_amount" class="textbox w200" validation="Number" value="0"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="sms_sender_name">SMS Sender Name</label>
        <input inputtype="textbox" id="sms_sender_name" name="sms_sender_name" class="textbox w200" validation="String" min="2" maxlength="11"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="sms" style="float:left; padding-right: 5px">SMS</label>
        <textarea inputtype="_default" id="sms" name="sms" class="textbox w200" rows="4"></textarea>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="address">Address</label>
        <input inputtype="textbox" id="address" name="address" class="textbox w200" validation="String" min="2" maxlength="300"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="phone">Phone</label>
        <input inputtype="textbox" id="phone" name="phone" class="textbox w200" validation="String" maxlength="11"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <center><button>Create</button></center>
    </div>
</form>