<?php
require 'require-admin.php';
require 'models/impl/HotelUserService.php';
require 'models/impl/HotelService.php';

$views['users'] = 'views/backend/hotel/users.php';
$views['user-reload'] = 'views/backend/hotel/user-reload.php';
$views['user-new'] = 'views/backend/hotel/user-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            HotelUserService::GetUsers();
            require $views['user-reload'];
            break;
        case 'new':
            UserService::GetUserNamesByType('Hotel');
            HotelService::GetHotelNames();
            require $views['user-new'];
            break;
        case 'insert':
            HotelUserService::DoInsert();
            require $views['user-reload'];
            break;
        case 'delete':
            HotelUserService::DoDelete();
            break;
    }
    return;
}

HotelUserService::GetUsers();
require $views['users'];
?>
