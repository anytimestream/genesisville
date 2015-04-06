<?php
require 'require-admin.php';
require 'models/impl/PCPlanService.php';

$views['plans'] = 'views/backend/pc/plans.php';
$views['plan-reload'] = 'views/backend/pc/plan-reload.php';
$views['plan-new'] = 'views/backend/pc/plan-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            PCPlanService::GetPCPlans();
            require $views['plan-reload'];
            break;
        case 'update':
            PCPlanService::DoUpdate();
            require $views['plan-reload'];
            break;
        case 'new':
            require $views['plan-new'];
            break;
        case 'insert':
            PCPlanService::DoInsert();
            require $views['plan-reload'];
            break;
        case 'delete':
            PCPlanService::DoDelete();
            break;
    }
    return;
}

PCPlanService::GetPCPlans();
require $views['plans'];
?>
