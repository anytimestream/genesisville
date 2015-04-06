<?php
require 'models/impl/HotspotService.php';
require 'models/impl/PhoneNumberService.php';

$views['online-subscription'] = 'views/hotspot/online-subscription2.php';

HotspotService::NewSubscription2();

require $views['online-subscription'];
?>
