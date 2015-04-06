<?php
require 'require-station.php';
require 'models/impl/TicketService.php';
require 'models/impl/SMSService.php';
require 'models/impl/PhoneNumberService.php';
require 'models/impl/StationService.php';

$views['tickets'] = 'views/station/tickets.php';
$views['order-form'] = 'views/station/order-form.php';
$views['order-success'] = 'views/station/order-success.php';
$views['plan-not-found'] = 'views/station/plan-not-found.php';

StationService::GetStationReportBalance();

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
    
    TicketService::PlaceStationOrder();
    require $views['order-success'];
    return;
}

TicketService::GetStationTickets();
require $views['tickets'];
?>
