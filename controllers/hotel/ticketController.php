<?php
require 'require-hotel.php';
require 'models/impl/TicketService.php';
require 'models/impl/HotelService.php';
require 'models/impl/SMSService.php';
require 'models/impl/PhoneNumberService.php';

$views['tickets'] = 'views/hotel/tickets.php';
$views['order-form'] = 'views/hotel/order-form.php';
$views['order-success'] = 'views/hotel/order-success.php';
$views['plan-not-found'] = 'views/hotel/plan-not-found.php';

HotelService::GetHotelMonthlyPoints();

if (isset($_GET['plan'])) {
    if (!TicketService::AssertCard()) {
        require $views['plan-not-found'];
        return;
    }
    if (!isset($_POST['phone'])) {
        TicketService::NewOrder();
        require $views['order-form'];
        return;
    }
    if (strlen($_POST['phone']) < 11 || substr($_POST['phone'], 0, 1) != "0" || !is_numeric($_POST['phone'])) {
        TicketService::NewOrder();
        require $views['order-form'];
        return;
    }
    
    TicketService::PlaceHotelOrder();
    require $views['order-success'];
    return;
}

TicketService::GetHotelTickets();
require $views['tickets'];
?>
