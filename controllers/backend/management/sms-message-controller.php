<?php

require 'require-admin.php';
require 'models/impl/SMSMessageService.php';

$views['messages'] = 'views/backend/management/sms-messages.php';
$views['messages-reload'] = 'views/backend/management/sms-messages-reload.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            SMSMessageService::GetMessages();
            require $views['messages-reload'];
            break;
        case 'update':
            SMSMessageService::DoUpdate();
            require $views['messages-reload'];
            break;
    }
    return;
}

SMSMessageService::GetMessages();
require $views['messages'];
?>
