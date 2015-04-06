<?php

class SMSService {

    public static function SendHotspotTicketByOrderId($id, $useDefaultGateway) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('CardOrder');
            $sql = "select c.username,c.password,o.plan,o.phone from " . CardOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p Inner Join " . Card::GetDSN() . " as c on o.id = p.related_to and p.transaction_id = c.transaction_id where o.id = ?";
            $orders = $query->executeQuery($sql, array($id), 0, 1);
            if ($orders->count() == 0) {
                throw new Exception("Invalid Order");
            }
            $order = $orders[0];
            $plan = $pm->getObjectByColumn('Plan', 'name', $order->getValue('plan'));

            $smsMessage = $pm->getObjectByColumn('SMSMessage', 'type', 'hotspot-send-sms');
            $message = $smsMessage->getValue('message');
            $sender = str_replace(' ', '+', $smsMessage->getValue('sender'));
            $message = str_replace('{username}', $order->getValue('username'), $message);
            $message = str_replace('{password}', trim($order->getValue('password')), $message);
            $message = str_replace('{validity}', $plan->getValue('validity'), $message);
            $message = str_replace('{uptime}', $plan->getValue('uptime'), $message);
            $message = str_replace(' ', '+', $message);
            
            self::SendSMS($message, $order->getValue('phone'), $sender, $useDefaultGateway);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            MailService::SendError("Hotspot - SendHotspotTicketByOrderId", $e->getMessage());
        }
    }
    
    public static function SendPrepaidUserRegistration($user_id, $password, $phone) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();

            $smsMessage = $pm->getObjectById('SMSMessage', 'prepaid_new_user');
            $message = $smsMessage->getValue('message');
            $sender = str_replace(' ', '+', $smsMessage->getValue('sender'));
            $message = str_replace('{user_id}', $user_id, $message);
            $message = str_replace('{password}', $password, $message);
            $message = str_replace(' ', '+', $message);
            
            self::SendSMS($message, $phone, $sender, true);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function SendPCUserRegistration($user_id, $password, $phone) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();

            $smsMessage = $pm->getObjectById('SMSMessage', 'pc_new_user');
            $message = $smsMessage->getValue('message');
            $sender = str_replace(' ', '+', $smsMessage->getValue('sender'));
            $message = str_replace('{user_id}', 'PC'.$user_id, $message);
            $message = str_replace('{password}', $password, $message);
            $message = str_replace(' ', '+', $message);
            
            self::SendSMS($message, $phone, $sender, true);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function SendPrepaidUserActivation($plan, $expiry, $tenure, $phone, $user_id) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();

            $smsMessage = $pm->getObjectById('SMSMessage', 'prepaid_plan_activation');
            $message = $smsMessage->getValue('message');
            $sender = str_replace(' ', '+', $smsMessage->getValue('sender'));
            $message = str_replace('{plan}', $plan, $message);
            $message = str_replace('{user_id}', $user_id, $message);
            $message = str_replace('{expires}', $expiry, $message);
            $message = str_replace('{tenure}', $tenure, $message);
            $message = str_replace(' ', '+', $message);
            
            self::SendSMS($message, $phone, $sender, true);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function SendPCUserActivation($plan, $expiry, $tenure, $phone, $user_id) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();

            $smsMessage = $pm->getObjectById('SMSMessage', 'pc_plan_activation');
            $message = $smsMessage->getValue('message');
            $sender = str_replace(' ', '+', $smsMessage->getValue('sender'));
            $message = str_replace('{plan}', $plan, $message);
            $message = str_replace('{user_id}', 'PC'.$user_id, $message);
            $message = str_replace('{expires}', $expiry, $message);
            $message = str_replace('{tenure}', $tenure, $message);
            $message = str_replace(' ', '+', $message);
            
            self::SendSMS($message, $phone, $sender, true);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function SendPrepaidRecurringPayment($plan, $expiry, $tenure, $amount, $phone, $user_id) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();

            $smsMessage = $pm->getObjectById('SMSMessage', 'prepaid_recurring_payment');
            $message = $smsMessage->getValue('message');
            $sender = str_replace(' ', '+', $smsMessage->getValue('sender'));
            $message = str_replace('{plan}', $plan, $message);
            $message = str_replace('{user_id}', $user_id, $message);
            $message = str_replace('{expires}', $expiry, $message);
            $message = str_replace('{tenure}', $tenure, $message);
            $message = str_replace('{amount}', $amount, $message);
            $message = str_replace(' ', '+', $message);
            
            self::SendSMS($message, $phone, $sender, true);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function SendPCRecurringPayment($plan, $expiry, $tenure, $amount, $phone, $user_id) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();

            $smsMessage = $pm->getObjectById('SMSMessage', 'prepaid_recurring_payment');
            $message = $smsMessage->getValue('message');
            $sender = str_replace(' ', '+', $smsMessage->getValue('sender'));
            $message = str_replace('{plan}', $plan, $message);
            $message = str_replace('{user_id}', 'PC'.$user_id, $message);
            $message = str_replace('{expires}', $expiry, $message);
            $message = str_replace('{tenure}', $tenure, $message);
            $message = str_replace('{amount}', $amount, $message);
            $message = str_replace(' ', '+', $message);
            
            self::SendSMS($message, $phone, $sender, true);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function SendPrepaidUserPasswordChange($password, $userId, $phone) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();

            $smsMessage = $pm->getObjectById('SMSMessage', 'prepaid_change_password');
            $message = $smsMessage->getValue('message');
            $sender = str_replace(' ', '+', $smsMessage->getValue('sender'));
            $message = str_replace('{user_id}', $userId, $message);
            $message = str_replace('{password}', $password, $message);
            $message = str_replace(' ', '+', $message);
            
            self::SendSMS($message, $phone, $sender, true);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function SendPCUserPasswordChange($password, $userId, $phone) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();

            $smsMessage = $pm->getObjectById('SMSMessage', 'prepaid_change_password');
            $message = $smsMessage->getValue('message');
            $sender = str_replace(' ', '+', $smsMessage->getValue('sender'));
            $message = str_replace('{user_id}', 'PC'.$userId, $message);
            $message = str_replace('{password}', $password, $message);
            $message = str_replace(' ', '+', $message);
            
            self::SendSMS($message, $phone, $sender, true);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function SendStationTicketByOrderId($id, $useDefaultGateway) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('StationOrder');
            $sql = "select c.username,c.password,o.plan,o.phone,s.sms_sender_name,s.sms from " . StationOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p Inner Join " . Card::GetDSN() . " as c inner join ".Station::GetDSN()." as s on o.id = p.related_to and p.transaction_id = c.transaction_id and o.station = s.name where o.id = ?";
            $orders = $query->executeQuery($sql, array($id), 0, 1);
            if ($orders->count() == 0) {
                throw new Exception("Invalid Order");
            }
            $order = $orders[0];
            $plan = $pm->getObjectByColumn('Plan', 'name', $order->getValue('plan'));

            
            $message = $order->getValue('sms');
            $sender = str_replace(' ', '+', $order->getValue('sms_sender_name'));
            $message = str_replace('{username}', $order->getValue('username'), $message);
            $message = str_replace('{password}', trim($order->getValue('password')), $message);
            $message = str_replace('{validity}', $plan->getValue('validity'), $message);
            $message = str_replace('{uptime}', $plan->getValue('uptime'), $message);
            $message = str_replace(' ', '+', $message);
            
            self::SendSMS($message, $order->getValue('phone'), $sender, $useDefaultGateway);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function SendHotelTicketByOrderId($id, $useDefaultGateway) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('HotelOrder');
            $sql = "select c.username,c.password,o.plan,o.phone,h.sms_sender_name,h.sms from " . HotelOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p Inner Join " . Card::GetDSN() . " as c inner join ".Hotel::GetDSN()." as h on o.id = p.related_to and p.transaction_id = c.transaction_id and o.hotel = h.name where o.id = ?";
            $orders = $query->executeQuery($sql, array($id), 0, 1);
            if ($orders->count() == 0) {
                throw new Exception("Invalid Order");
            }
            $order = $orders[0];
            $plan = $pm->getObjectByColumn('Plan', 'name', $order->getValue('plan'));

            
            $message = $order->getValue('sms');
            $sender = str_replace(' ', '+', $order->getValue('sms_sender_name'));
            $message = str_replace('{username}', $order->getValue('username'), $message);
            $message = str_replace('{password}', trim($order->getValue('password')), $message);
            $message = str_replace('{validity}', $plan->getValue('validity'), $message);
            $message = str_replace('{uptime}', $plan->getValue('uptime'), $message);
            $message = str_replace(' ', '+', $message);
            
            self::SendSMS($message, $order->getValue('phone'), $sender, $useDefaultGateway);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function SendPrepaidReminder($user_id, $expires, $days, $phone) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $smsMessage = $pm->getObjectById('SMSMessage', 'payment_reminder');
            $message = $smsMessage->getValue('message');
            $sender = str_replace(' ', '+', $smsMessage->getValue('sender'));
            $message = str_replace('{days}', $days, $message);
            $message = str_replace('{user_id}', $user_id, $message);
            $message = str_replace('{expires}', $expires, $message);
            $message = str_replace(' ', '+', $message);
            
            self::SendSMS($message,$phone, $sender, true);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function SendTicketReminder($plan, $available) {
        try {
            $sender = 'GenesisISP';
            $phone = "08064363747";
            $message = 'Plan availability alert. Available cards for Plan '.$plan.' is: '.$available;
            $message = str_replace(' ', '+', $message);
            
            self::SendSMS($message, $phone, $sender, true);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function SendSMS($message, $phone, $sender, $useDefaultGateway) {
        try {
            error_reporting(0);
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('SMSGateway');
            $gateways = $query->executeQuery('select name from ' . SMSGateway::GetDSN() . ' order by isDefault desc', array(), 0, 2);
            if ($useDefaultGateway) {
                if ($gateways[0]->getValue('name') == 'smsVilla') {
                    try {
                        $str = 'http://www.estoresms.com/smsapi.php?username=genesisville&password=dubaxtra&sender=' . $sender . '&recipient=' . $phone . '&message=' . $message;
                        //$str = 'http://www.bulksmsvilla.com/api/?type=send-sms&email=genesisville@yahoo.com&sub-account=GenesisISP&subaccount-password=gville&sender-id='.$sender.'&message='.$message.'&phone-number='.$phone.'&msg-type=1';
                        //$str = "http://way2txtsms.com/sendsms/groupsms.php?username=gea337db&password=80cb68ad&type=TEXT&sender=".$sender."&mobile=234".substr($phone, 1)."&message=".$message;
                        file_get_contents($str);
                    } catch (Exception $e) {
                        throw $e;
                    }
                } else {
                    try {
                        $str = "http://sms.shreeweb.com/sendsms/sendsms.php?username=genesvile&password=KZ5mJmDv&type=TEXT&sender=".$sender."&mobile=234".substr($phone, 1)."&message=".$message;
                        //$str = "http://www.smslive247.com/http/index.aspx?cmd=sendquickmsg&owneremail=gilgalextra@yahoo.com&subacct=genesis&subacctpwd=admin&message=" . $message . "&sender=" . $sender . "&sendto=" . $phone . "&msgtype=0";
                        file_get_contents($str);
                    } catch (Exception $e) {
                        throw $e;
                    }
                }
            } else {
                if ($gateways[1]->getValue('name') == 'smsVilla') {
                    try {
                        $str = 'http://www.estoresms.com/smsapi.php?username=genesisville&password=dubaxtra&sender=' . $sender . '&recipient=' . $phone . '&message=' . $message;
                        file_get_contents($str);
                    } catch (Exception $e) {
                        throw $e;
                    }
                } else {
                    try {
                        $str = "http://sms.shreeweb.com/sendsms/sendsms.php?username=genesvile&password=KZ5mJmDv&type=TEXT&sender=".$sender."&mobile=234".substr($phone, 1)."&message=".$message;
                        file_get_contents($str);
                    } catch (Exception $e) {
                        throw $e;
                    }
                }
            }
        } catch (Exception $e) {
            MailService::SendError("Hotspot - Send SMS", $e->getMessage());
        }
    }

}

?>
