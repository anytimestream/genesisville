<?php
require 'require-admin.php';
require 'models/impl/HotelService.php';

$views['report-form'] = 'views/backend/hotel/report-form.php';
$views['report'] = 'views/backend/hotel/report.php';

if (isset($_POST['begin'])) {
    HotelService::GetReport();
    require $views['report'];
    return;
}

HotelService::GetHotelNames();
require $views['report-form'];
?>
