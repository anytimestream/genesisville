<?php
require 'require-prepaid.php';
require 'models/impl/PrepaidUserService.php';

if(isset($_POST['name'])){
    PrepaidUserService::UpdateUserData();
}
else{
    PrepaidUserService::GetUserData();
}
require 'views/prepaid/personal-details.php';
?>
