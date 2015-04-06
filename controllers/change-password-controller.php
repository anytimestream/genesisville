<?php
require 'models/impl/UserService.php';
require 'models/impl/PasswordChangeRequestService.php';

if(!UserService::IsAuthenticated() || UserService::GetUser()->getValue('type') == 'PrepaidUser'){
    UserService::RequireLogin(CONTEXT_PATH.'/login');
}

$views['change-password'] = 'views/change-password.php';

if (isset($_POST['password'])) {
    UserService::DoChangePassword();
}
require $views['change-password'];
?>
