<iframe id="change_password_frame" name="change_password_frame" style="border: 0; width: 100%;height: 40px;overflow: auto">
</iframe>
<form class="normosa-ui-form" target="change_password_frame" method="post" action="<?php echo CONTEXT_PATH ?>/backend/plantation-city/users?action=update-password">
    <div class="row">
        <label class="label" for="password">Password</label>
        <input inputtype="textbox" type="password" id="password" name="password" class="textbox w200" validation="String" min="3" maxlength="20"/>
        <input inputtype="_default" type="hidden" id="id" name="id" class="textbox w200" value="<?php echo $_GET['id']?>"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <center><button>Update</button></center>
    </div>
</form>
