<?php
require 'models/impl/HotspotService.php';
require 'models/impl/PrepaidOrderService.php';
require 'models/impl/SMSService.php';

$views['notify'] = 'views/hotspot/notify.php';

HotspotService::AcceptPayment2();

require $views['notify'];
?>
