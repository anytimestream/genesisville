<form class="normosa-ui-form" method="post" action="<?php echo CONTEXT_PATH ?>/backend/management/users?action=insert">
    <div class="row">
        <label class="label" for="username">Username</label>
        <input inputtype="textbox" id="username" name="username" class="textbox w200" validation="String" min="3" maxlength="20"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="password">Password</label>
        <input inputtype="textbox" type="password" id="password" name="password" class="textbox w200" validation="String" min="4" maxlength="20"/>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <label class="label" for="type">Type</label>
        <select inputtype="_default" id="type" name="type" class="textbox w200 h23">
            <option>Station</option>
            <option>Hotel</option>
            <option>Admin</option>
        </select>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
    <div class="row">
        <center><button>Create</button></center>
    </div>
</form>