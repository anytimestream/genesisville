<?php
require 'require-hotel.php';
require 'models/impl/HotelService.php';

$views['report-form'] = 'views/hotel/report-form.php';
$views['report'] = 'views/hotel/report.php';

if (isset($_POST['begin'])) {
    HotelService::GetHotelReport();
    require $views['report'];
    return;
}

HotelService::GetHotelMonthlyPoints();
require $views['report-form'];
?>
