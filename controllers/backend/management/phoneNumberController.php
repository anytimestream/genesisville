<?php
require 'require-admin.php';
require 'models/impl/PhoneNumberService.php';

$views['phone-numbers'] = 'views/backend/management/phone-numbers.php';
$views['phone-number-reload'] = 'views/backend/management/phone-number-reload.php';
$views['phone-number-new'] = 'views/backend/management/phone-number-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            PhoneNumberService::GetPhoneNumbers();
            require $views['phone-number-reload'];
            break;
        case 'download':
            PhoneNumberService::DoDownload();
            break;
    }
    return;
}

PhoneNumberService::GetDistinctTypes();
PhoneNumberService::GetPhoneNumbers();
require $views['phone-numbers'];
?>
