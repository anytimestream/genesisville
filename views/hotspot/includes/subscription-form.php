<form method="post" action="">
    <div class=row>
        <center><input style="margin-left: 5px;" type="radio" name="plan" id="plan" value="<?php echo $_GET['plan']->getValue('name') ?>" checked/><label style="margin-left: 5px;color:#c00;font-size:15px; "><?php echo $_GET['plan']->getValue('description') . ' - N' . $_GET['plan']->getValue('amount') ?></label></center>
    </div><br/>
    <div class=row>
        <label class="label w100">Phone</label><input style="margin-left: 5px;" inputtype="textbox" class="textbox w200" name="phone" id="phone" value="<?php echo $_GET['phone'] ?>"/>
    </div>
    <?php
    if (isset($_POST['phone'])) {
        if (strlen($_POST['phone']) < 11 || substr($_POST['phone'], 0, 1) != "0" || !is_numeric($_POST['phone'])) {
            ?><center><span style="display:inline-block;line-height: 25px;color:#00ff00">Invalid phone number</span></center><?php
    }
}
    ?>
    <div class=row>
        <label class="label w100">Confirm Phone</label><input style="margin-left: 5px;" inputtype="textbox" class="textbox w200" name="phone2" id="phone2" value="<?php echo $_GET['phone2'] ?>"/>
    </div>
    <?php
    if (isset($_POST['phone']) && $_POST['phone'] != $_POST['phone2']) {
        ?><center><span style="display:inline-block;line-height: 25px;color:#00ff00">Phone number mismatch</span></center><?php
}
    ?>
    <div class=row>
        <label class="label w100">Email</label><input style="margin-left: 5px;text-transform:lowercase" inputtype="textbox" class="textbox w200" name="email" id="email" value="<?php echo $_GET['email'] ?>"/>
    </div>
    <?php
    if (isset($_POST['email']) && !preg_match('/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i', $_POST['email'])) {
        ?><center><span style="display:inline-block;line-height: 25px;color:#00ff00">Invalid email</span></center><?php
}
    ?>
    <div class=row>
        <center><button style="margin-left: 5px;">Subscribe</button></center>
    </div>
</form>
