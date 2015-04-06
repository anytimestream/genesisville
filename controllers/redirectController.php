<?php
require 'models/impl/UserService.php';

if (UserService::IsAuthenticated()) {
    if(UserService::GetUser()->getValue('type') == 'Admin'){
        header('Location: '.CONTEXT_PATH.'/backend');
    }
    else if(UserService::GetUser()->getValue('type') == 'Hotel'){
        header('Location: '.CONTEXT_PATH.'/hotel');
    }
    else if(UserService::GetUser()->getValue('type') == 'Station'){
        header('Location: '.CONTEXT_PATH.'/station');
    }
    else if(UserService::GetUser()->getValue('type') == 'Prepaid'){
        header('Location: '.CONTEXT_PATH.'/prepaid');
    }
} else {
    header('Location: '.CONTEXT_PATH.'/login');
}
?>
