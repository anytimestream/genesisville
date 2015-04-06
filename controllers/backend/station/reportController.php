<?php
require 'require-admin.php';
require 'models/impl/StationService.php';

$views['report-form'] = 'views/backend/station/report-form.php';
$views['report'] = 'views/backend/station/report.php';

if (isset($_POST['begin'])) {
    StationService::GetReport();
    require $views['report'];
    return;
}

StationService::GetStationNames();
require $views['report-form'];
?>
