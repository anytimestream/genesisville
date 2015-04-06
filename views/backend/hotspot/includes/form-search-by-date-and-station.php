<form class="normosa-ui-form" id="search_form" method="get" action="">
    <label>From&nbsp;</label>
    <input inputtype="date" class="textbox w80" numberOfMonths="1" id="from" name="from" value="<?php if(isset($_GET['from'])) {echo $_GET['from'];} else {echo date('d/m/Y');} ?>"/>
    <label>&nbsp;&nbsp;To&nbsp;</label>
    <input inputtype="date" class="textbox w80" numberOfMonths="1" id="to" name="to" value="<?php if(isset($_GET['to'])) {echo $_GET['to'];} else {echo date('d/m/Y');} ?>"/>
    <select inputtype="_default" id="station" name="station" style="width: 150px">
        <option value="all">All Stations</option>
        <?php
        $stations = $_GET['stations'];
        for ($i = 0; $i < $stations->count(); $i++) {
            if (isset($_GET['station']) && urldecode($_GET['station']) == $stations[$i]->getValue('name')) {
                echo "<option selected value=\"" . urlencode($stations[$i]->getValue('name')) . "\">" . $stations[$i]->getValue('name') . "</option>";
            } else {
                echo "<option value=\"" . urlencode($stations[$i]->getValue('name')) . "\">" . $stations[$i]->getValue('name') . "</option>";
            }
        }
        ?>
    </select>
    <button class="btn" style="border:0; font-size: 12px;float: none">Search</button>
</form>
