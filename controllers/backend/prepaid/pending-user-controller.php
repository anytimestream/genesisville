<?php
require 'require-admin.php';
require 'models/impl/PrepaidUserService.php';
require 'models/impl/PrepaidPlanService.php';
require 'models/impl/AgentService.php';
require 'models/impl/SMSService.php';

$views['users'] = 'views/backend/prepaid/pending-users.php';
$views['user-reload'] = 'views/backend/prepaid/pending-user-reload.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            PrepaidUserService::GetPrepaidPendingUsers();
            require $views['user-reload'];
            break;
        case 'update':
            PrepaidUserService::DoUpdate();
            require $views['user-reload'];
            break;
        case 'accept':
            PrepaidUserService::Accept();
            break;
    }
    return;
}

PrepaidUserService::GetPrepaidPendingUsers();
require $views['users'];
?>
