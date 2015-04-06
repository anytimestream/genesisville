<?php
require 'require-station.php';
require 'models/impl/StationService.php';

$views['report-form'] = 'views/station/report-form.php';
$views['report'] = 'views/station/report.php';

if (isset($_POST['begin'])) {
    StationService::GetStationReport();
    require $views['report'];
    return;
}

StationService::GetStationReportBalance();
require $views['report-form'];
?>
