<form class="normosa-ui-form" id="search_form" method="get" action="">
    <label>From&nbsp;</label>
    <input inputtype="date" class="textbox w80" numberOfMonths="1" id="from" name="from" value="<?php if(isset($_GET['from'])) {echo $_GET['from'];} else {echo date('d/m/Y');} ?>"/>
    <label>&nbsp;&nbsp;To&nbsp;</label>
    <input inputtype="date" class="textbox w80" numberOfMonths="1" id="to" name="to" value="<?php if(isset($_GET['to'])) {echo $_GET['to'];} else {echo date('d/m/Y');} ?>"/>
    <select inputtype="_default" id="hotel" name="hotel" style="width: 150px">
        <option value="all">All Hotels</option>
        <?php
        $hotels = $_GET['hotels'];
        for ($i = 0; $i < $hotels->count(); $i++) {
            if (isset($_GET['hotel']) && urldecode($_GET['hotel']) == $hotels[$i]->getValue('name')) {
                echo "<option selected value=\"" . urlencode($hotels[$i]->getValue('name')) . "\">" . $hotels[$i]->getValue('name') . "</option>";
            } else {
                echo "<option value=\"" . urlencode($hotels[$i]->getValue('name')) . "\">" . $hotels[$i]->getValue('name') . "</option>";
            }
        }
        ?>
    </select>
    <button class="btn" style="border:0; font-size: 12px;float: none">Search</button>
</form>