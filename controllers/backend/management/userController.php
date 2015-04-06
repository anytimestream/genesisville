<?php
require 'require-admin.php';
require 'models/impl/SMSService.php';

$views['users'] = 'views/backend/management/users.php';
$views['user-reload'] = 'views/backend/management/user-reload.php';
$views['user-new'] = 'views/backend/management/user-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            UserService::GetUsers();
            require $views['user-reload'];
            break;
        case 'new':
            require $views['user-new'];
            break;
        case 'insert':
            UserService::DoInsert();
            require $views['user-reload'];
            break;
        case 'update':
            if(isset($_POST['password'])){
                UserService::DoUpdate();
                require 'views/backend/management/user-change-password-success.php';
            }
            else{
                require 'views/backend/management/user-change-password-form.php';
            }
            break;
        case 'delete':
            UserService::DoDelete();
            break;
    }
    return;
}

UserService::GetUsers();
require $views['users'];
?>
