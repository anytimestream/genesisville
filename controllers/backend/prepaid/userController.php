<?php
require 'require-admin.php';
require 'models/impl/PrepaidUserService.php';
require 'models/impl/PrepaidPlanService.php';
require 'models/impl/AgentService.php';
require 'models/impl/SMSService.php';

$views['users'] = 'views/backend/prepaid/users.php';
$views['user-reload'] = 'views/backend/prepaid/user-reload.php';
$views['user-new'] = 'views/backend/prepaid/user-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            PrepaidUserService::GetPrepaidUsers();
            require $views['user-reload'];
            break;
        case 'update':
            PrepaidUserService::DoUpdate();
            require $views['user-reload'];
            break;
        case 'update-password':
            if(isset($_POST['password'])){
                PrepaidUserService::DoUpdatePassword();
                require 'views/backend/prepaid/user-change-password-success.php';
            }
            else{
                require 'views/backend/prepaid/user-change-password-form.php';
            }
            break;
    }
    return;
}

PrepaidUserService::GetPrepaidUsers();
require $views['users'];
?>
