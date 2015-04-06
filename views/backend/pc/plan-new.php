<form class="normosa-ui-form" method="post" action="<?php echo CONTEXT_PATH ?>/backend/plantation-city/plans?action=insert">
    <div class="row">
        <label class="label" for="name">Name</label>
        <input inputtype="textbox" id="name" name="name" class="textbox w200" validation="String" min="2" maxlength="30"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="description">Description</label>
        <input inputtype="textbox" id="description" name="description" class="textbox w200" validation="String" maxlength="100"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="amount">Amount</label>
        <input inputtype="textbox" id="amount" name="amount" class="textbox w200" validation="Number" min="1"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <center><button>Create</button></center>
    </div>
</form>