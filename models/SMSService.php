<?php

class SMSService {

    public static function Send($username, $password, $phone, $plan, $station, $useDefaultGateway) {
        $pm = PersistenceManager::NewPersistenceManager();
        $query = $pm->getQueryBuilder('Station');
        $stations = $query->executeQuery('select sms, smsSenderName from ' . Station::GetDSN() . ' where name = ?', array($station), 0, 1);
        if ($stations->count() == 0) {
            throw new Exception('Internal error occured');
        }
        $message = $stations[0]->getValue('sms');
        $sender = str_replace(" ", "+", $stations[0]->getValue('smsSenderName'));
        $message = str_replace('{username}', $username, $message);
        $message = str_replace('{password}', trim($password), $message);
        $message = str_replace('{validity}', $plan->getValue('validity'), $message);
        $message = str_replace('{uptime}', $plan->getValue('uptime'), $message);
        $message = str_replace(' ', '+', $message);
        $query = $pm->getQueryBuilder('SMSGateway');
        $gateways = $query->executeQuery('select name from ' . SMSGateway::GetDSN() . ' order by isDefault desc', array(), 0, 2);
        if ($useDefaultGateway) {
            if ($gateways[0]->getValue('name') == 'eStore') {
                try {
                    $str = 'http://www.estoresms.com/smsapi.php?username=genesisville&password=dubaxtra&sender=' . $sender . '&recipient=' . $phone . '&message=' . $message;
                    file_get_contents($str);
                } catch (Exception $e) {
                    
                }
            } else {
                try {
                    $str = "http://www.smslive247.com/http/index.aspx?cmd=sendquickmsg&owneremail=gilgalextra@yahoo.com&subacct=genesis&subacctpwd=admin&message=" . $message . "&sender=" . $sender . "&sendto=" . $phone . "&msgtype=0";
                    file_get_contents($str);
                } catch (Exception $e) {
                    
                }
            }
        } else {
            if ($gateways[1]->getValue('name') == 'eStore') {
                try {
                    $str = 'http://www.estoresms.com/smsapi.php?username=genesisville&password=dubaxtra&sender=' . $sender . '&recipient=' . $phone . '&message=' . $message;
                    file_get_contents($str);
                } catch (Exception $e) {
                    
                }
            } else {
                try {
                    $str = "http://www.smslive247.com/http/index.aspx?cmd=sendquickmsg&owneremail=gilgalextra@yahoo.com&subacct=genesis&subacctpwd=admin&message=" . $message . "&sender=" . $sender . "&sendto=" . $phone . "&msgtype=0";
                    file_get_contents($str);
                } catch (Exception $e) {
                    
                }
            }
        }
    }

    public static function SendMessage($sender, $phone, $message, $useDefaultGateway) {
        $pm = PersistenceManager::NewPersistenceManager();
        $query = $pm->getQueryBuilder('SMSGateway');
        $gateways = $query->executeQuery('select name from ' . SMSGateway::GetDSN() . ' order by isDefault desc', array(), 0, 2);
        if ($useDefaultGateway) {
            if ($gateways[0]->getValue('name') == 'eStore') {
                try {
                    $str = 'http://www.estoresms.com/smsapi.php?username=genesisville&password=dubaxtra&sender=' . $sender . '&recipient=' . $phone . '&message=' . $message;
                    file_get_contents($str);
                } catch (Exception $e) {

                }
            } else {
                try {
                    $str = "http://www.smslive247.com/http/index.aspx?cmd=sendquickmsg&owneremail=gilgalextra@yahoo.com&subacct=genesis&subacctpwd=admin&message=" . $message . "&sender=" . $sender . "&sendto=" . $phone . "&msgtype=0";
                    file_get_contents($str);
                } catch (Exception $e) {

                }
            }
        } else {
            if ($gateways[1]->getValue('name') == 'eStore') {
                try {
                    $str = 'http://www.estoresms.com/smsapi.php?username=genesisville&password=dubaxtra&sender=' . $sender . '&recipient=' . $phone . '&message=' . $message;
                    file_get_contents($str);
                } catch (Exception $e) {

                }
            } else {
                try {
                    $str = "http://www.smslive247.com/http/index.aspx?cmd=sendquickmsg&owneremail=gilgalextra@yahoo.com&subacct=genesis&subacctpwd=admin&message=" . $message . "&sender=" . $sender . "&sendto=" . $phone . "&msgtype=0";
                    file_get_contents($str);
                } catch (Exception $e) {

                }
            }
        }
    }

}

?>
