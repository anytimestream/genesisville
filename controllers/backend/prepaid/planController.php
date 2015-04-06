<?php
require 'require-admin.php';
require 'models/impl/PrepaidPlanService.php';

$views['plans'] = 'views/backend/prepaid/plans.php';
$views['plan-reload'] = 'views/backend/prepaid/plan-reload.php';
$views['plan-new'] = 'views/backend/prepaid/plan-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            PrepaidPlanService::GetPrepaidPlans();
            require $views['plan-reload'];
            break;
        case 'update':
            PrepaidPlanService::DoUpdate();
            require $views['plan-reload'];
            break;
        case 'new':
            require $views['plan-new'];
            break;
        case 'insert':
            PrepaidPlanService::DoInsert();
            require $views['plan-reload'];
            break;
        case 'delete':
            PrepaidPlanService::DoDelete();
            break;
    }
    return;
}

PrepaidPlanService::GetPrepaidPlans();
require $views['plans'];
?>
