<?php
require 'require-admin.php';
require 'models/impl/CardService.php';
require 'models/impl/PlanService.php';

$views['cards'] = 'views/backend/hotspot/cards.php';
$views['card-reload'] = 'views/backend/hotspot/card-reload.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            CardService::GetCards();
            require $views['card-reload'];
            break;
    }
    return;
}

if(isset($_FILES['image'])) {
    CardService::DoInsert();
}

PlanService::GetPlanNames();
CardService::GetCards();
require $views['cards'];
?>
