<?php

require 'require-admin.php';
require 'models/impl/PasswordChangeRequestService.php';
require_once 'models/impl/SMSService.php';

$views['password-change-request'] = 'views/backend/prepaid/password-change-requests.php';
$views['password-change-request-reload'] = 'views/backend/prepaid/password-change-request-reload.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            PasswordChangeRequestService::Get();
            require $views['password-change-request-reload'];
            break;
        case 'activate':
            PasswordChangeRequestService::Activate();
            break;
    }
    return;
}

PasswordChangeRequestService::Get();
require $views['password-change-request'];
?>
