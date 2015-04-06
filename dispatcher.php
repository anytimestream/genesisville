<?php
require 'config.php';
require 'rewrite-url.php';
require 'global-includes.php';

session_start();

//patch
$viewControllers[] = array('path' => '/patch/card-order', 'controller' => 'patch/card-order.php');
$viewControllers[] = array('path' => '/patch/prepaid-order', 'controller' => 'patch/prepaid-order.php');

$viewControllers[] = array('path' => '/backend/reminders/tickets', 'controller' => 'backend/reminders/tickets-controller.php');
$viewControllers[] = array('path' => '/backend/reminders/prepaid', 'controller' => 'backend/reminders/prepaid-controller.php');

$viewControllers[] = array('path' => '/login', 'controller' => 'loginController.php', 'view' => 'login.php');
$viewControllers[] = array('path' => '/prepaid-login', 'controller' => 'prepaid-login-controller.php', 'view' => 'prepaid-login.php');
$viewControllers[] = array('path' => '/change-password', 'controller' => 'change-password-controller.php', 'view' => 'change-password.php');
$viewControllers[] = array('path' => '/redirect', 'controller' => 'redirectController.php');

$viewControllers[] = array('path' => '/backend', 'controller' => 'backend/homeController.php');
//hotspot
$viewControllers[] = array('path' => '/backend/hotspot/plans', 'controller' => 'backend/hotspot/planController.php', 'view' => 'backend/hotspot/plans.php');
$viewControllers[] = array('path' => '/backend/hotspot/orders', 'controller' => 'backend/hotspot/orderController.php', 'view' => 'backend/hotspot/orders.php');
$viewControllers[] = array('path' => '/backend/hotspot/pending-orders', 'controller' => 'backend/hotspot/pending-order-controller.php', 'view' => 'backend/hotspot/pending-orders.php');
$viewControllers[] = array('path' => '/backend/hotspot/cards', 'controller' => 'backend/hotspot/cardController.php', 'view' => 'backend/hotspot/cards.php');

$viewControllers[] = array('path' => '/backend/station/plans', 'controller' => 'backend/station/planController.php', 'view' => 'backend/station/plans.php');
$viewControllers[] = array('path' => '/backend/station/orders', 'controller' => 'backend/station/orderController.php', 'view' => 'backend/station/orders.php');
$viewControllers[] = array('path' => '/backend/station/stations', 'controller' => 'backend/station/stationController.php', 'view' => 'backend/station/stations.php');
$viewControllers[] = array('path' => '/backend/station/users', 'controller' => 'backend/station/userController.php', 'view' => 'backend/station/users.php');
$viewControllers[] = array('path' => '/backend/station/remittance', 'controller' => 'backend/station/remittanceController.php', 'view' => 'backend/station/remittance.php');
$viewControllers[] = array('path' => '/backend/station/report', 'controller' => 'backend/station/reportController.php', 'view' => 'backend/station/report.php');
$viewControllers[] = array('path' => '/backend/station/prepaid', 'controller' => 'backend/station/prepaidController.php', 'view' => 'backend/station/prepaid.php');

$viewControllers[] = array('path' => '/backend/hotel/plans', 'controller' => 'backend/hotel/planController.php', 'view' => 'backend/hotel/plans.php');
$viewControllers[] = array('path' => '/backend/hotel/orders', 'controller' => 'backend/hotel/orderController.php', 'view' => 'backend/hotel/orders.php');
$viewControllers[] = array('path' => '/backend/hotel/hotels', 'controller' => 'backend/hotel/hotelController.php', 'view' => 'backend/hotel/hotels.php');
$viewControllers[] = array('path' => '/backend/hotel/report', 'controller' => 'backend/hotel/reportController.php', 'view' => 'backend/hotel/report.php');
$viewControllers[] = array('path' => '/backend/hotel/users', 'controller' => 'backend/hotel/userController.php', 'view' => 'backend/hotel/users.php');

$viewControllers[] = array('path' => '/backend/prepaid/plans', 'controller' => 'backend/prepaid/planController.php', 'view' => 'backend/prepaid/plans.php');
$viewControllers[] = array('path' => '/backend/prepaid/orders', 'controller' => 'backend/prepaid/orderController.php', 'view' => 'backend/prepaid/orders.php');
$viewControllers[] = array('path' => '/backend/prepaid/agents', 'controller' => 'backend/prepaid/agentController.php', 'view' => 'backend/prepaid/agents.php');
$viewControllers[] = array('path' => '/backend/prepaid/users', 'controller' => 'backend/prepaid/userController.php', 'view' => 'backend/prepaid/users.php');
$viewControllers[] = array('path' => '/backend/prepaid/pending-users', 'controller' => 'backend/prepaid/pending-user-controller.php', 'view' => 'backend/prepaid/pending-users.php');
$viewControllers[] = array('path' => '/backend/prepaid/pending-orders', 'controller' => 'backend/prepaid/pending-order-controller.php', 'view' => 'backend/prepaid/pending-orders.php');
$viewControllers[] = array('path' => '/backend/prepaid/password-change-requests', 'controller' => 'backend/prepaid/password-change-request-controller.php');

//plantation city
$viewControllers[] = array('path' => '/backend/plantation-city/plans', 'controller' => 'backend/pc/planController.php', 'view' => 'backend/pc/plans.php');
$viewControllers[] = array('path' => '/backend/plantation-city/orders', 'controller' => 'backend/pc/orderController.php', 'view' => 'backend/pc/orders.php');
$viewControllers[] = array('path' => '/backend/plantation-city/users', 'controller' => 'backend/pc/userController.php', 'view' => 'backend/pc/users.php');
//$viewControllers[] = array('path' => '/backend/plantation-city/password-change-requests', 'controller' => 'backend/pc/password-change-request-controller.php');

$viewControllers[] = array('path' => '/backend/management/users', 'controller' => 'backend/management/userController.php', 'view' => 'backend/management/users.php');
$viewControllers[] = array('path' => '/backend/management/sms-messages', 'controller' => 'backend/management/sms-message-controller.php', 'view' => 'backend/management/sms-messages.php');
$viewControllers[] = array('path' => '/backend/management/sms-gateways', 'controller' => 'backend/management/sms-gateway-controller.php', 'view' => 'backend/management/sms-gateways.php');
$viewControllers[] = array('path' => '/backend/management/phone-numbers', 'controller' => 'backend/management/phoneNumberController.php', 'view' => 'backend/management/phone-numbers.php');

$viewControllers[] = array('path' => '/hotspot/online-subscription', 'controller' => 'hotspot/hotspotController.php', 'view' => 'hotspot/online-subscription.php');
//$viewControllers[] = array('path' => '/hotspot/online-subscription2', 'controller' => 'hotspot/hotspot2Controller.php', 'view' => 'hotspot/online-subscription2.php');
$viewControllers[] = array('path' => '/hotspot/notify', 'controller' => 'hotspot/notifyController.php', 'view' => 'hotspot/notify.php');
$viewControllers[] = array('path' => '/hotspot/notify2', 'controller' => 'hotspot/notify2Controller.php', 'view' => 'hotspot/notify2.php');

$viewControllers[] = array('path' => '/station', 'controller' => 'station/ticketController.php', 'view' => 'station/tickets.php');
$viewControllers[] = array('path' => '/station/tickets', 'controller' => 'station/ticketController.php', 'view' => 'station/tickets.php');
$viewControllers[] = array('path' => '/station/orders', 'controller' => 'station/orderController.php', 'view' => 'station/orders.php');
$viewControllers[] = array('path' => '/station/report', 'controller' => 'station/reportController.php', 'view' => 'station/report.php');
$viewControllers[] = array('path' => '/station/prepaid-orders', 'controller' => 'station/prepaid-order-controller.php');

$viewControllers[] = array('path' => '/hotel', 'controller' => 'hotel/ticketController.php', 'view' => 'hotel/tickets.php');
$viewControllers[] = array('path' => '/hotel/tickets', 'controller' => 'hotel/ticketController.php', 'view' => 'hotel/tickets.php');
$viewControllers[] = array('path' => '/hotel/orders', 'controller' => 'hotel/orderController.php', 'view' => 'hotel/orders.php');
$viewControllers[] = array('path' => '/hotel/report', 'controller' => 'hotel/reportController.php', 'view' => 'hotel/report.php');

$viewControllers[] = array('path' => '/prepaid/signup', 'controller' => 'prepaid/signup-controller.php', 'view' => 'prepaid/signup.php');
$viewControllers[] = array('path' => '/prepaid', 'controller' => 'prepaid/new-subscription-controller.php');
$viewControllers[] = array('path' => '/prepaid/home', 'controller' => 'prepaid/home-controller.php');
$viewControllers[] = array('path' => '/prepaid/personal-details', 'controller' => 'prepaid/personal-details-controller.php');
$viewControllers[] = array('path' => '/prepaid/new-subscription', 'controller' => 'prepaid/new-subscription-controller.php');
$viewControllers[] = array('path' => '/prepaid/recurring-subscription-notify', 'controller' => 'prepaid/recurring-subscription-notify-controller.php');
$viewControllers[] = array('path' => '/prepaid/new-subscription-notify', 'controller' => 'prepaid/new-subscription-notify-controller.php');
$viewControllers[] = array('path' => '/prepaid/payment-history', 'controller' => 'prepaid/payment-history-controller.php');

$defaultViewController = array('path' => '/', 'controller' => 'homeController.php', 'view' => 'home.php');

$_404ViewController = array('path' => '/', 'controller' => null, 'view' => '404.php');

dispatchRequest();

function dispatchRequest() {
    $uri = $_SERVER['REQUEST_URI'];
    $strpos = strpos($uri, '?');
    if(strlen($strpos) > 0){
        $uri = substr($uri, 0, $strpos);
    }
    $viewController = getViewController($uri);
    if ($viewController['controller'] != null) {
        require 'controllers/' . $viewController['controller'];
    }
    else{
        require 'views/' . $viewController['view'];
    }
}

function getViewController($uri) {
    global $viewControllers;
    global $defaultViewController;
    global $_404ViewController;

    if ($uri == '/') {
        return $defaultViewController;
    }

    foreach ($viewControllers as $viewController) {
        $strpos = strpos($viewController['path'], '/*');
        if (strlen($strpos) > 0) {
            if(substr($uri, 0, $strpos) == substr($viewController['path'], 0, $strpos)){
                return $viewController;
            }
        } else {
            if ($uri == $viewController['path'] || $uri == $viewController['path'] . '/') {
                return $viewController;
            }
        }
    }

    return $_404ViewController;
}
?>
