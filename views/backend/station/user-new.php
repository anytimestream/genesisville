<form class="normosa-ui-form" method="post" action="<?php echo CONTEXT_PATH ?>/backend/station/users?action=insert">
    <div class="row">
        <label class="label" for="user">User</label>
        <select inputtype="_default" id="user" name="user" class="textbox w200 h23">
            <?php
            $users = $_GET['users'];
            for ($i = 0; $i < $users->count(); $i++) {
                ?>
                <option><?php echo $users[$i]->getValue('username') ?></option>
                <?php
            }
            ?>
        </select>
        <span class="error" style="display: block;padding-left: 210px; color: #c02;font-size: 11px; line-height: 20px; height: 20px"></span>
    </div>
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
        <center><button>Create</button></center>
    </div>
</form>