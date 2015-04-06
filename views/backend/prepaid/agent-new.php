<form class="normosa-ui-form" method="post" action="<?php echo CONTEXT_PATH ?>/backend/prepaid/agents?action=insert">
    <div class="row">
        <label class="label" for="firstname">First Name</label>
        <input inputtype="textbox" id="firstname" name="firstname" class="textbox w200" validation="String" min="2" maxlength="15"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="lastname">Last Name</label>
        <input inputtype="textbox" id="lastname" name="lastname" class="textbox w200" validation="String" min="2" maxlength="15"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="phone">Phone</label>
        <input inputtype="textbox" id="phone" name="phone" class="textbox w200" validation="String" maxlength="11"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="email">Email</label>
        <input inputtype="textbox" id="email" name="email" style="text-transform: lowercase" class="textbox w200" validation="Email" allownull="true" maxlength="200"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <center><button>Create</button></center>
    </div>
</form>