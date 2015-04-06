<div style="float:right" class="pagination">
    <?php
    $pagination = $_GET['pagination'];
    if ($pagination->getNext() > $pagination->getPrevious()) {
        ?>
        <a class="btn" action="back" style="border-left: 1px solid #e0e2e2;" href="<?php echo CONTEXT_PATH . $pagination->getUrl() ?>page=<?php echo $pagination->getPrevious() ?>">Back</a>
        <input type="text" value="<?php echo $pagination->getIndex() ?>" />
        <a class="btn" action="next" max="<?php echo $pagination->getMaxPage() ?>" href="<?php echo CONTEXT_PATH . $pagination->getUrl() ?>page=<?php echo $pagination->getNext() ?>">Next</a>
        <?php
    }
    ?>
</div>