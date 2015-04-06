<?php
if(isset($_GET['error'])){
    require 'includes/error.php';
    return;
}
$messages = $_GET['messages'];
for ($i = 0; $i < $messages->count(); $i++) {
    ?>
    <div class="tr notselected" id="<?php echo $messages[$i]->getValue('id')?>">
    <span class="td select" id="<?php echo $messages[$i]->getValue('id')?>" title="Select"></span>
    <span class="td w150"><?php echo $messages[$i]->getValue('type')?></span>
    <span class="td w500"><?php echo $messages[$i]->getValue('message')?></span>
    <span class="td w150"><?php echo $messages[$i]->getValue('sender')?></span>
    </div>
    <?php
}