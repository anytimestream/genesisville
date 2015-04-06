<?php
require 'require-admin.php';
require 'models/impl/StationPlanService.php';
require 'models/impl/PlanService.php';
require 'models/impl/StationService.php';

$views['plans'] = 'views/backend/station/plans.php';
$views['plan-reload'] = 'views/backend/station/plan-reload.php';
$views['plan-new'] = 'views/backend/station/plan-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            StationPlanService::GetPlans();
            require $views['plan-reload'];
            break;
        case 'update':
            StationPlanService::DoUpdate();
            require $views['plan-reload'];
            break;
        case 'new':
            PlanService::GetPlanNames();
            StationService::GetStationNames();
            require $views['plan-new'];
            break;
        case 'insert':
            StationPlanService::DoInsert();
            require $views['plan-reload'];
            break;
        case 'delete':
            StationPlanService::DoDelete();
            break;
    }
    return;
}

StationPlanService::GetPlans();
require $views['plans'];
?>
