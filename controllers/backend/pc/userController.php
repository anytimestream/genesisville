<?php
require 'require-admin.php';
require 'models/impl/PCUserService.php';
require 'models/impl/PCPlanService.php';
require 'models/impl/SMSService.php';

$views['users'] = 'views/backend/pc/users.php';
$views['user-reload'] = 'views/backend/pc/user-reload.php';
$views['user-new'] = 'views/backend/pc/user-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            PCUserService::GetPCUsers();
            require $views['user-reload'];
            break;
        case 'update':
            PCUserService::DoUpdate();
            require $views['user-reload'];
            break;
        case 'update-password':
            if(isset($_POST['password'])){
                PCUserService::DoUpdatePassword();
                require 'views/backend/pc/user-change-password-success.php';
            }
            else{
                require 'views/backend/pc/user-change-password-form.php';
            }
            break;
    }
    return;
}

PCUserService::GetPCUsers();
require $views['users'];
?>
