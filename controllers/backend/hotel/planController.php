<?php
require 'require-admin.php';
require 'models/impl/HotelPlanService.php';
require 'models/impl/PlanService.php';
require 'models/impl/HotelService.php';

$views['plans'] = 'views/backend/hotel/plans.php';
$views['plan-reload'] = 'views/backend/hotel/plan-reload.php';
$views['plan-new'] = 'views/backend/hotel/plan-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            HotelPlanService::GetPlans();
            require $views['plan-reload'];
            break;
        case 'new':
            PlanService::GetPlanNames();
            HotelService::GetHotelNames();
            require $views['plan-new'];
            break;
        case 'update':
            HotelPlanService::DoUpdate();
            require $views['plan-reload'];
            break;
        case 'insert':
            HotelPlanService::DoInsert();
            require $views['plan-reload'];
            break;
        case 'delete':
            HotelPlanService::DoDelete();
            break;
    }
    return;
}

HotelPlanService::GetPlans();
require $views['plans'];
?>
