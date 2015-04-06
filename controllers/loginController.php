<?php
require 'models/impl/UserService.php';

$views['login'] = 'views/login.php';

if (UserService::IsAuthenticated()) {
    UserService::Logout();
} else if (isset($_POST['username'])) {
    UserService::Login($_POST['username'], $_POST['password'], CONTEXT_PATH.'/redirect');
}

require $views['login'];
?>
