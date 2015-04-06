<?php
require 'require-admin.php';
require 'models/impl/StationUserService.php';
require 'models/impl/StationService.php';

$views['users'] = 'views/backend/station/users.php';
$views['user-reload'] = 'views/backend/station/user-reload.php';
$views['user-new'] = 'views/backend/station/user-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            StationUserService::GetUsers();
            require $views['user-reload'];
            break;
        case 'new':
            UserService::GetUserNamesByType('Station');
            StationService::GetStationNames();
            require $views['user-new'];
            break;
        case 'insert':
            StationUserService::DoInsert();
            require $views['user-reload'];
            break;
        case 'delete':
            StationUserService::DoDelete();
            break;
    }
    return;
}

StationUserService::GetUsers();
require $views['users'];
?>
