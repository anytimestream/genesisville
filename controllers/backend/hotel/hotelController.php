<?php
require 'require-admin.php';
require 'models/impl/HotelService.php';

$views['hotels'] = 'views/backend/hotel/hotels.php';
$views['hotel-reload'] = 'views/backend/hotel/hotel-reload.php';
$views['hotel-new'] = 'views/backend/hotel/hotel-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            HotelService::GetHotels();
            require $views['hotel-reload'];
            break;
        case 'update':
            HotelService::DoUpdate();
            require $views['hotel-reload'];
            break;
        case 'new':
            require $views['hotel-new'];
            break;
        case 'insert':
            HotelService::DoInsert();
            require $views['hotel-reload'];
            break;
        case 'delete':
            HotelService::DoDelete();
            break;
    }
    return;
}

HotelService::GetHotels();
require $views['hotels'];
?>
