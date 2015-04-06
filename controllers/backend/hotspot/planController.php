<?php
require 'require-admin.php';
require 'models/impl/PlanService.php';

$views['plans'] = 'views/backend/hotspot/plans.php';
$views['plan-reload'] = 'views/backend/hotspot/plan-reload.php';
$views['plan-new'] = 'views/backend/hotspot/plan-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            PlanService::GetPlans();
            require $views['plan-reload'];
            break;
        case 'update':
            PlanService::DoUpdate();
            require $views['plan-reload'];
            break;
        case 'new':
            require $views['plan-new'];
            break;
        case 'insert':
            PlanService::DoInsert();
            require $views['plan-reload'];
            break;
        case 'delete':
            PlanService::DoDelete();
            break;
    }
    return;
}

PlanService::GetPlans();
require $views['plans'];
?>
