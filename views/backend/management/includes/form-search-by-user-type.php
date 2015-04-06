<form class="normosa-ui-form" id="search_form" method="get" action="">
    <label>Type&nbsp;</label>
    <select inputtype="_default" id="type" name="type" style="width: 100px">
        <option value="station" <?php if(isset($_GET['type']) && $_GET['type'] == 'station'){echo 'selected';}?>>Station</option>
        <option value="hotel" <?php if(isset($_GET['type']) && $_GET['type'] == 'hotel'){echo 'selected';}?>>Hotel</option>
        ?>
    </select>
    <button class="btn" style="border:0; font-size: 12px;float: none">Search</button>
</form>