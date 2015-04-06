<form class="normosa-ui-form" id="search_form" method="get" action="">
    <label>From&nbsp;</label>
    <input inputtype="date" class="textbox w80" numberOfMonths="1" id="from" name="from" value="<?php if(isset($_GET['from'])) {echo $_GET['from'];} else { $now = strtotime(date('d/m/Y')); echo date('d/m/Y', ($now - (365 * 24 * 60 * 60)));} ?>"/>
    <label>&nbsp;&nbsp;To&nbsp;</label>
    <input inputtype="date" class="textbox w80" numberOfMonths="1" id="to" name="to" value="<?php if(isset($_GET['to'])) {echo $_GET['to'];} else {echo date('d/m/Y');} ?>"/>
    <button class="btn" style="border:0; font-size: 12px;float: none">Search</button>
</form>