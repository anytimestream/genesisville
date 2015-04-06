<?php

class PrepaidUserService {

    public static function GetPrepaidUsers() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array();
            $csql = "select count(u.id) from " . PrepaidUser::GetDSN() . " as u inner join " . PrepaidUserAccountNumber::GetDSN() . " as a on u.id = a.prepaid_user";
            $sql = "select u.id,a.account_number as username,u.name,u.address,u.city,u.state,u.phone,u.email,u.agent,u.creation_date,u.last_changed from " . PrepaidUser::GetDSN() . " as u inner join " . PrepaidUserAccountNumber::GetDSN() . " as a on u.id = a.prepaid_user order by a.account_number";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/prepaid/users?');
            $pagination->setPages();
            $users = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['users'] = $users;
            $_GET['pagination'] = $pagination;
            AgentService::GetAgentNames();
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetPrepaidPendingUsers() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array(0);
            $csql = "select count(u.id) from " . PrepaidUser::GetDSN() . " as u where u.state = ?";
            $sql = "select * from " . PrepaidUser::GetDSN() . " where state = ? order by creation_date desc";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/prepaid/pending-users?');
            $pagination->setPages();
            $users = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['users'] = $users;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetPrepaidUserNames() {
        try {
            $values = array();
            $sql = "select u.id,a.account_number as user_id,u.name from " . PrepaidUser::GetDSN() . " as u inner join " . PrepaidUserAccountNumber::GetDSN() . " as a on u.id = a.prepaid_user order by a.account_number";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $users = $query->executeQuery($sql, $values, 0, 100000);
            $_GET['users'] = $users;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetUserData() {
        try {
            $values = array(UserService::GetUser()->getValue('username'));
            $sql = "select p.name,p.address,p.city,p.phone,p.email from " . PrepaidUser::GetDSN() . " as p inner join " . PrepaidUserAccountNumber::GetDSN() . " as a inner join " . User::GetDSN() . " as u on p.id = a.prepaid_user and a.account_number = u.username where u.username = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $users = $query->executeQuery($sql, $values, 0, 1);
            $_GET['user'] = $users[0];
            $_GET['name'] = $_GET['user']->getValue('name');
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoUpdatePassword() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'password');
            $assertProperties->addProperty($_POST, 'id');
            $assertProperties->assert();

            $sql = "select a.account_number,u.phone from " . PrepaidUser::GetDSN() . " as u inner join " . PrepaidUserAccountNumber::GetDSN() . " as a on u.id = a.prepaid_user where u.id = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $users = $query->executeQuery($sql, array($_POST['id']), 0, 1);
            $user = $pm->getObjectByColumn('User', 'username',$users[0]->getValue('account_number'));
            $user->setValue('password', md5($_POST['password'] . $user->getValue('id') . 'genesisville'));
            $pm->save($user);
            SMSService::SendPrepaidUserPasswordChange($_POST['password'], $users[0]->getValue('account_number'), $users[0]->getValue('phone'));
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function UpdateUserData() {
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'name');
            $assertProperties->addProperty($_POST, 'phone');
            $assertProperties->addProperty($_POST, 'email');
            $assertProperties->addProperty($_POST, 'address');
            $assertProperties->addProperty($_POST, 'city');
            $assertProperties->assert();
            $values = array(UserService::GetUser()->getValue('username'));
            $sql = "select p.id from " . PrepaidUser::GetDSN() . " as p inner join " . PrepaidUserAccountNumber::GetDSN() . " as a inner join " . User::GetDSN() . " as u on p.id = a.prepaid_user and a.account_number = u.username where u.username = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $users = $query->executeQuery($sql, $values, 0, 1);
            $user = $pm->getObjectById('PrepaidUser', $users[0]->getValue('id'));
            $user->setValue('name', $_POST['name']);
            $user->setValue('phone', $_POST['phone']);
            $user->setValue('email', $_POST['email']);
            $user->setValue('address', $_POST['address']);
            $user->setValue('city', $_POST['city']);
            $pm->save($user);
            $_GET['msg'] = 'Personal Details Updated';
            $_GET['user'] = $user;
            $_GET['name'] = $_GET['user']->getValue('name');
        } catch (Exception $e) {
            echo $e;
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetLoginUserName() {
        try {
            $values = array(UserService::GetUser()->getValue('username'));
            $sql = "select p.name from " . PrepaidUser::GetDSN() . " as p inner join " . PrepaidUserAccountNumber::GetDSN() . " as a inner join " . User::GetDSN() . " as u on p.id = a.prepaid_user and a.account_number = u.username where u.username = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $users = $query->executeQuery($sql, $values, 0, 1);
            return $users[0]->getValue('name');
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoUpdate() {
        try {

            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'data');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $data = json_decode(stripslashes($_POST['data']), true);

            $users = new PersistableListObject(null, null);

            foreach ($data as $id => $row) {

                $assertProperties->addProperty($row, 'agent');
                $assertProperties->addProperty($row, 'state');
                $assertProperties->addProperty($row, 'name');
                $assertProperties->addProperty($row, 'phone');
                $assertProperties->addProperty($row, 'email');
                $assertProperties->addProperty($row, 'address');
                $assertProperties->addProperty($row, 'city');
                $assertProperties->assert();

                $user = $pm->getObjectById('PrepaidUser', $id);

                $pm->beginTransaction();

                $sms = false;
                $order = null;

                $user->setValue('agent', $row['agent']);
                if ($user->getValue('state') == 0 && $row['state'] == 1) {
                    $values = array($user->getValue('id'), 3);
                    $sql = "select o.id from " . PrepaidOrder::GetDSN() . " as o where o.user = ? and o.status = ? order by o.creation_date desc";
                    $query = $pm->getQueryBuilder('PrepaidUser');
                    $orders = $query->executeQuery($sql, $values, 0, 1);
                    $order = $pm->getObjectById('PrepaidOrder', $orders[0]->getValue('id'));
                    $order->setValue('state', 1);
                    $pm->save($order);
                    $sms = true;
                    $user->setValue('state', $row['state']);
                }
                $user->setValue('name', $row['name']);
                $user->setValue('phone', $row['phone']);
                $user->setValue('email', $row['email']);
                $user->setValue('address', $row['address']);
                $user->setValue('city', $row['city']);

                $pm->save($user);

                $pm->commit();

                $sql = "select u.id,a.account_number as username,u.name,u.address,u.city,u.state,u.phone,u.email,u.agent,u.creation_date,u.last_changed from " . PrepaidUser::GetDSN() . " as u inner join " . PrepaidUserAccountNumber::GetDSN() . " as a on u.id = a.prepaid_user where u.id = ?";
                $query = $pm->getQueryBuilder('PrepaidUser');

                $_users = $query->executeQuery($sql, array($user->getValue('id')), 0, 1);
                $users->add($_users[0]);

                if ($sms) {
                    SMSService::SendPrepaidUserActivation($order->getValue('plan'), $order->getValue('expires'), ($order->getValue('tenure') * 30), $user->getValue('phone'));
                }
            }

            $_GET['users'] = $users;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoInsert() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'plan');
            $assertProperties->addProperty($_POST, 'tenure');
            $assertProperties->addProperty($_POST, 'name');
            $assertProperties->addProperty($_POST, 'password');
            $assertProperties->addProperty($_POST, 'phone');
            $assertProperties->addProperty($_POST, 'email');
            $assertProperties->addProperty($_POST, 'address');
            $assertProperties->addProperty($_POST, 'city');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $pm->beginTransaction();
            $prepaidUser = new PrepaidUser();
            $sql = 'select name from ' . Agent::GetDSN() . ' where deleted = ? order by creation_date desc';
            $query = $pm->getQueryBuilder('Agent');
            $agents = $query->executeQuery($sql, array(0), 0, 1);
            $prepaidUser->setValue('agent', $agents[0]->getValue('name'));
            $prepaidUser->setValue('name', $_POST['name']);
            $prepaidUser->setValue('phone', $_POST['phone']);
            $prepaidUser->setValue('email', $_POST['email']);
            $prepaidUser->setValue('address', $_POST['address']);
            $prepaidUser->setValue('city', $_POST['city']);
            $prepaidUser->setValue('state', 0);
            $pm->save($prepaidUser);

            $user = new User();
            $user->setValue('type', 'Prepaid');
            $user->setValue('password', $_POST['password']);
            $user->setValue('username', $prepaidUser->getValue('id'));
            $pm->save($user);

            $order = new PrepaidOrder();
            $order->setValue('user', $prepaidUser->getValue('id'));
            $order->setValue('tenure', $_POST['tenure']);
            $order->setValue('plan', $_POST['plan']);
            $order->setValue('state', 0);
            $order->setValue('status', 0);
            if ($_POST['method'] == '1') {
                $order->setValue('method', "Online");
            } else {
                $order->setValue('method', "Bank");
            }

            $begin_parts = explode('/', date('d/m/Y'));
            $begin = mktime(0, 0, 0, $begin_parts[1], $begin_parts[0], $begin_parts[2]);
            $expires = $begin + (60 * 60 * 24 * 30 * $_POST['tenure']);
            $order->setValue('expires', date('Y-m-d', $expires));
            $order->setValue('posted_by', $user->getValue('username'));
            $plan = $pm->getObjectByColumn('PrepaidPlan', 'name', $order->getValue('plan'));
            $amount = $order->getValue('tenure') * $plan->getValue('amount');
            $order->setValue('amount', $amount);
            $pm->save($order);

            $pm->commit();

            $_GET['order'] = $order;

            if ($_POST['method'] == '1') {
                $_GET['amount'] = $amount;
                $_GET['notify-url'] = CONTEXT_PATH . '/prepaid/new-subscription-notify';
                $_GET['view'] = 'payment-form.php';
            } else {
                $_GET['view'] = 'bank-details.php';
            }
        } catch (Exception $e) {
            $pm->rollBack();
            $_GET['error'] = $e->getMessage();
            $_GET['view'] = 'signup-form.php';
        }
    }

    public static function Notify() {
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'transaction_id');
            $assertProperties->assert();

            $json = file_get_contents('https://voguepay.com/?v_transaction_id=' . $_POST['transaction_id'] . '&type=json');
            $transaction = json_decode($json, true);
            if ($transaction['total'] == 0)
                throw new Exception('Invalid Total');

            $pm = PersistenceManager::NewPersistenceManager();
            $order = $pm->getObjectById('PrepaidOrder', $transaction['merchant_ref']);
            if ($order == null) {
                throw new Exception("Order not found");
            }

            if (strlen($order->getValue('transaction_id')) > 0) {
                throw new Exception("Already completed transaction");
            }

            $pm->beginTransaction();

            $payment = new Payment();
            $payment->setValue('amount', $order->getValue('amount'));
            $payment->setValue('status', 1);
            $payment->setValue('type', 'Prepaid');
            $payment->setValue('method', 'ePayment');
            $payment->setValue('related_to', $order->getValue('id'));
            $pm->save($payment);

            $order->setValue('transaction_id', $transaction['transaction_id']);
            $order->setValue('method', $transaction['method']);
            $order->setValue('referrer', $transaction['referrer']);
            if ($transaction['total'] == $order->getValue('amount') && $transaction['status'] == 'Approved') {
                $order->setValue('status', 3);
            } else if ($transaction['total'] == $order->getValue('amount') && $transaction['status'] == 'Disputed') {
                $order->setValue('status', 1);
            } else if ($transaction['total'] == $order->getValue('amount') && $transaction['status'] == 'Failed') {
                $order->setValue('status', 2);
            }
            $pm->save($order);

            $prepaidUserAccountNumber = new PrepaidUserAccountNumber();
            $prepaidUserAccountNumber->setValue('prepaid_user', $order->getValue('user'));
            $prepaidUserAccountNumber->setValue('account_number', self::GetNewAccountNumber());
            $pm->save($prepaidUserAccountNumber);

            $user = $pm->getObjectByColumn('User', 'username', $order->getValue('user'));
            $user->setValue('type', 'Prepaid');
            $password = $user->getPassword('password');
            $user->setValue('password', md5($user->getPassword('password') . $user->getValue('id') . 'genesisville'));
            $user->setValue('username', $prepaidUserAccountNumber->getValue('account_number'));
            $pm->save($user);

            $prepaidUser = $pm->getObjectById('PrepaidUser', $order->getValue('user'));
            $prepaidUser->setValue('state', 1);
            $pm->save($prepaidUser);

            if ($transaction['total'] == $order->getValue('amount') && $transaction['status'] == 'Approved') {
                SMSService::SendPrepaidUserRegistration($prepaidUserAccountNumber->getValue('account_number'), $password, $prepaidUser->getValue('phone'));
                SMSService::SendSMS(str_replace(" ", "+", "Hi, You have a pending Prepaid User Registration"), "2348064363747", "GenesisISP", true);
            }

            $pm->commit();

            $_GET['view'] = 'payment-accepted.php';
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            $_GET['view'] = 'error.php';
            MailService::SendError("Prepaid - New ePayment", $e->getMessage());
        }
    }
    
    public static function GetNewAccountNumber(){
        $pm = PersistenceManager::NewPersistenceManager();
        $sql = "select account_number from ".PrepaidUserAccountNumber::GetDSN()." order by account_number desc";
        $query = $pm->getQueryBuilder('PrepaidUserAccountNumber');
        $prepaidUserAccountNumbers = $query->executeQuery($sql, array(), 0, 1);
        if($prepaidUserAccountNumbers->count() > 0){
            return ($prepaidUserAccountNumbers[0]->getValue('account_number') + 1);
        }
        else{
            return 10000;
        }
    }

    public static function Accept() {
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_GET, 'id');
            $assertProperties->assert();

            $pm = PersistenceManager::NewPersistenceManager();
            $order = $pm->getObjectByColumn('PrepaidOrder', 'user', $_GET['id']);
            if ($order == null) {
                throw new Exception("Order not found");
            }

            if (strlen($order->getValue('transaction_id')) > 0) {
                throw new Exception("Already completed transaction");
            }

            $pm->beginTransaction();

            $payment = new Payment();
            $payment->setValue('amount', $order->getValue('amount'));
            $payment->setValue('status', 1);
            $payment->setValue('type', 'Prepaid');
            $payment->setValue('method', 'ePayment');
            $payment->setValue('related_to', $order->getValue('id'));
            $pm->save($payment);

            $order->setValue('transaction_id', 'force accept');
            $order->setValue('method', 'Bank');
            $order->setValue('status', 3);
            $order->setValue('state', 1);
            $pm->save($order);

            $prepaidUserAccountNumber = new PrepaidUserAccountNumber();
            $prepaidUserAccountNumber->setValue('prepaid_user', $order->getValue('user'));
            $prepaidUserAccountNumber->setValue('account_number', self::GetNewAccountNumber());
            $pm->save($prepaidUserAccountNumber);

            $user = $pm->getObjectByColumn('User', 'username', $order->getValue('user'));
            $user->setValue('type', 'Prepaid');
            $password = $user->getValue('password');
            $user->setValue('password', md5($password . $user->getValue('id') . 'genesisville'));
            $user->setValue('username', $prepaidUserAccountNumber->getValue('account_number'));
            $pm->save($user);

            $prepaidUser = $pm->getObjectById('PrepaidUser', $order->getValue('user'));
            $prepaidUser->setValue('state', 1);
            $pm->save($prepaidUser);

            $pm->commit();

            SMSService::SendPrepaidUserRegistration($prepaidUserAccountNumber->getValue('account_number'), $password, $prepaidUser->getValue('phone'));
            header('Location: ' . CONTEXT_PATH . '/backend/prepaid/users');
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            $_GET['view'] = 'error.php';
            MailService::SendError("Prepaid - New ePayment", $e->getMessage());
        }
    }

    public static function DoDelete() {
        try {

            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'data');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();

            $data = json_decode(stripslashes($_POST['data']), true);
            foreach ($data as $row) {
                $pm->deleteByObjectId('PrepaidUser', $row);
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            echo $e;
        }
    }

    public static function GetCurrentInfo($id) {
        try {
            $values = array($id, 3);
            $sql = "select o.plan,o.expires from " . PrepaidOrder::GetDSN() . " as o where o.user = ? and o.status = ? order by o.creation_date desc";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $users = $query->executeQuery($sql, $values, 0, 1);
            return array('plan' => $users[0]->getValue('plan'), 'expires' => $users[0]->getValue('expires'));
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetCurrentUserInfo() {
        try {
            $values = array(UserService::GetUser()->getValue('username'), 3);
            $sql = "select o.plan,o.expires from " . PrepaidOrder::GetDSN() . " as o inner join ".PrepaidUserAccountNumber::GetDSN()." as a on o.user = a.prepaid_user where a.account_number = ? and o.status = ? order by o.creation_date desc";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $users = $query->executeQuery($sql, $values, 0, 1);
            return array('plan' => $users[0]->getValue('plan'), 'expires' => $users[0]->getValue('expires'));
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function GetCurrentPendingInfo($id) {
        try {
            $values = array($id, 3);
            $sql = "select o.plan,o.expires from " . PrepaidOrder::GetDSN() . " as o where o.user = ? and o.status < ? order by o.creation_date desc";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $users = $query->executeQuery($sql, $values, 0, 1);
            return array('plan' => $users[0]->getValue('plan'), 'expires' => $users[0]->getValue('expires'));
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function SendReminders() {
        try {
            $values = array();
            $sql = "select u.id,a.account_number as username,u.phone from " . PrepaidUser::GetDSN() . " as u inner join " . PrepaidUserAccountNumber::GetDSN() . " as a on u.id = a.prepaid_user";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $users = $query->executeQuery($sql, $values, 0, 100000);
            for ($i = 0; $i < $users->count(); $i++) {
                $info = self::GetCurrentInfo($users[$i]->getValue('id'));
                $expires = strtotime($info['expires']);
                $now = strtotime(date('Y-m-d'));
                if ($expires >= $now) {
                    $diff = abs($expires - $now);
                    $days = floor($diff / (60 * 60 * 24));
                    if ($days == 7 || $days == 2) {
                        SMSService::SendPrepaidReminder($users[$i]->getValue('username'), Date('d/m/Y', $expires), $days, $users[$i]->getValue('phone'));
                    }
                }
            }
        } catch (Exception $e) {
            MailService::SendError("Reminders - Prepaid", $e->getMessage());
        }
    }

}

?>
