<form class="normosa-ui-form" id="search_form" method="get" action="">
    <label>From&nbsp;</label>
    <input inputtype="date" class="textbox w80" numberOfMonths="1" id="from" name="from" value="<?php if(isset($_GET['from'])) {echo $_GET['from'];} else {echo date('d/m/Y');} ?>"/>
    <label>&nbsp;&nbsp;To&nbsp;</label>
    <input inputtype="date" class="textbox w80" numberOfMonths="1" id="to" name="to" value="<?php if(isset($_GET['to'])) {echo $_GET['to'];} else {echo date('d/m/Y');} ?>"/>
    <select inputtype="_default" id="user" name="user" style="width: 100px">
        <option value="all">All Users</option>
        <?php
        $users = $_GET['users'];
        for ($i = 0; $i < $users->count(); $i++) {
            if (isset($_GET['user']) && urldecode($_GET['user']) == $users[$i]->getValue('user')) {
                echo "<option selected value=\"" . urlencode($users[$i]->getValue('user')) . "\">" . $users[$i]->getValue('user') . "</option>";
            } else {
                echo "<option value=\"" . urlencode($users[$i]->getValue('user')) . "\">" . $users[$i]->getValue('user') . "</option>";
            }
        }
        ?>
    </select>
    <button class="btn" style="border:0; font-size: 12px;float: none">Search</button>
</form>