<?php
require 'models/impl/PrepaidUserService.php';
require 'models/impl/PCUserService.php';
require 'models/impl/SMSService.php';

PrepaidUserService::SendReminders();
PCUserService::SendReminders();
?>
