<form id="search_form" method="get" action="">
    <label>Type</label>
    <select inputtype="_default" id="type" name="type" style="width: 150px">
        <option value="all">All Types</option>
        <?php
        $types = $_GET['types'];
        for ($i = 0; $i < $types->count(); $i++) {
            if (isset($_GET['type']) && urldecode($_GET['type']) == $types[$i]->getValue('name')) {
                echo "<option selected value=\"" . urlencode($types[$i]->getValue('name')) . "\">" . $types[$i]->getValue('name') . "</option>";
            } else {
                echo "<option value=\"" . urlencode($types[$i]->getValue('name')) . "\">" . $types[$i]->getValue('name') . "</option>";
            }
        }
        ?>
    </select>
    <button class="btn" style="border:0; font-size: 12px;float: none">Search</button>
</form>