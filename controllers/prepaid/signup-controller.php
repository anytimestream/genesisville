<?php
require 'models/impl/UserService.php';
require 'models/impl/PrepaidPlanService.php';
require 'models/impl/PrepaidUserService.php';
require 'models/impl/AgentService.php';

PrepaidPlanService::GetPrepaidPlanNames();

if(isset($_POST['name'])){
    PrepaidUserService::DoInsert();
}
else{
    $_GET['view'] = 'signup-form.php';
}

require 'views/prepaid/signup.php';
?>
