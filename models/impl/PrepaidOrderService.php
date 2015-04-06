<?php

class PrepaidOrderService {

    public static function GetOrders() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array(3, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');
            $url_search = 'from=' . date('d/m/Y');
            $url_search .= '&to=' . date('d/m/Y');
            $summary_url = date('d/m/Y');

            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = explode("/", $_GET['from']);
                $to = explode("/", $_GET['to']);
                if (count($from) == 3 && count($to) == 3) {
                    $values[1] = $from[2] . '-' . Util::AddLeadingZeros($from[1], 2) . '-' . Util::AddLeadingZeros($from[0], 2) . ' 00:00:00';
                    $values[2] = $to[2] . '-' . Util::AddLeadingZeros($to[1], 2) . '-' . Util::AddLeadingZeros($to[0], 2) . ' 23:59:59';
                    $summary_url = $_GET['from'] . ' - ' . $_GET['to'];
                    $url_search = 'from=' . $_GET['from'];
                    $url_search .= '&to=' . $_GET['to'];
                }
            }

            $sql = "select o.id,o.plan,o.tenure,o.expires,a.account_number,o.posted_by,o.remarks,o.state,p.status,o.method,o.transaction_id as gateway_ref,p.amount,p.transaction_id,o.creation_date,o.last_changed from " . PrepaidOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p inner join " . PrepaidUserAccountNumber::GetDSN() . " as a on o.id = p.related_to and o.user = a.prepaid_user where o.status = ? and o.creation_date between ? and ?";
            $csql = "select count(*) from " . PrepaidOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.status = ? and o.creation_date between ? and ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidOrder');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/prepaid/orders?' . $url_search . '&');
            $pagination->setPages();
            $orders = $query->executeQuery($sql . ' order by o.creation_date desc', $values, (($index - 1) * $size), $size);
            $_GET['orders'] = $orders;
            $_GET['pagination'] = $pagination;
            $_GET['url_search'] = $url_search;
            $_GET['summary_url'] = $summary_url;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetPendingOrders() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array(3, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');
            $url_search = 'from=' . date('d/m/Y');
            $url_search .= '&to=' . date('d/m/Y');
            $summary_url = date('d/m/Y');

            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = explode("/", $_GET['from']);
                $to = explode("/", $_GET['to']);
                if (count($from) == 3 && count($to) == 3) {
                    $values[1] = $from[2] . '-' . Util::AddLeadingZeros($from[1], 2) . '-' . Util::AddLeadingZeros($from[0], 2) . ' 00:00:00';
                    $values[2] = $to[2] . '-' . Util::AddLeadingZeros($to[1], 2) . '-' . Util::AddLeadingZeros($to[0], 2) . ' 23:59:59';
                    $summary_url = $_GET['from'] . ' - ' . $_GET['to'];
                    $url_search = 'from=' . $_GET['from'];
                    $url_search .= '&to=' . $_GET['to'];
                }
            }

            $sql = "select o.id,o.plan,o.tenure,o.amount,o.expires,o.method,o.transaction_id as gateway_ref,o.creation_date,o.last_changed,u.name from " . PrepaidOrder::GetDSN() . " as o inner join " . PrepaidUser::GetDSN() . " as u on o.user = u.id where o.status < ? and o.creation_date between ? and ?";
            $csql = "select count(*) from " . PrepaidOrder::GetDSN() . " where status < ? and creation_date between ? and ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidOrder');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/prepaid/pending-orders?' . $url_search . '&');
            $pagination->setPages();
            $orders = $query->executeQuery($sql . ' order by o.creation_date desc', $values, (($index - 1) * $size), $size);
            $_GET['orders'] = $orders;
            $_GET['pagination'] = $pagination;
            $_GET['url_search'] = $url_search;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetStationOrders() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $pm = PersistenceManager::NewPersistenceManager();
            $station = $pm->getObjectByColumn('StationUser', 'user', UserService::GetUser()->getValue('username'))->getValue('station');
            $values = array(3, $station, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');
            $url_search = 'from=' . date('d/m/Y');
            $url_search .= '&to=' . date('d/m/Y');
            $summary_url = date('d/m/Y');

            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = explode("/", $_GET['from']);
                $to = explode("/", $_GET['to']);
                if (count($from) == 3 && count($to) == 3) {
                    $values[2] = $from[2] . '-' . Util::AddLeadingZeros($from[1], 2) . '-' . Util::AddLeadingZeros($from[0], 2) . ' 00:00:00';
                    $values[3] = $to[2] . '-' . Util::AddLeadingZeros($to[1], 2) . '-' . Util::AddLeadingZeros($to[0], 2) . ' 23:59:59';
                    $summary_url = $_GET['from'] . ' - ' . $_GET['to'];
                    $url_search = 'from=' . $_GET['from'];
                    $url_search .= '&to=' . $_GET['to'];
                }
            }

            $sql = "select o.id,o.plan,o.tenure,o.expires,a.account_number,o.posted_by,o.remarks,o.state,p.status,o.method,o.transaction_id as gateway_ref,p.amount,p.transaction_id,o.creation_date,o.last_changed from " . PrepaidOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p inner join " . PrepaidUserAccountNumber::GetDSN() . " as a on o.id = p.related_to and o.user = a.prepaid_user where o.status = ? and o.station = ? and o.creation_date between ? and ?";
            $csql = "select count(*) from " . PrepaidOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.status = ? and o.station = ? and o.creation_date between ? and ?";
            $query = $pm->getQueryBuilder('PrepaidOrder');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/prepaid/orders?' . $url_search . '&');
            $pagination->setPages();
            $orders = $query->executeQuery($sql . ' order by o.creation_date desc', $values, (($index - 1) * $size), $size);
            $_GET['orders'] = $orders;
            $_GET['pagination'] = $pagination;
            $_GET['url_search'] = $url_search;
            $_GET['summary_url'] = $summary_url;
            UserService::GetStationUserNames();
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function PrintByOrderId($id) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidOrder');
            $sql = "select o.id,o.plan,o.tenure,o.expires,a.account_number,o.posted_by,o.remarks,o.state,p.status,o.method,o.transaction_id as gateway_ref,p.amount,p.transaction_id,o.creation_date,o.last_changed from " . PrepaidOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p inner join " . PrepaidUserAccountNumber::GetDSN() . " as a on o.id = p.related_to and o.user = a.prepaid_user where o.id = ?";
            $orders = $query->executeQuery($sql, array($id), 0, 1);
            if ($orders->count() == 0) {
                throw new Exception("Invalid Order");
            }
            $_GET['order'] = $orders[0];
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetPrepaidOrders() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }

            $values = array(UserService::GetUser()->getValue('username'));
            $sql = "select p.id from " . PrepaidUser::GetDSN() . " as p inner join " . PrepaidUserAccountNumber::GetDSN() . " as a inner join " . User::GetDSN() . " as u on p.id = a.prepaid_user and a.account_number = u.username where u.username = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $users = $query->executeQuery($sql, $values, 0, 1);

            $user = $users[0]->getValue('id');
            $now = strtotime(date('d/m/Y'));
            $start = date('Y-m-d', ($now - (365 * 24 * 60 * 60)));
            $values = array(3, $user, $start . ' 00:00:00', date('Y-m-d') . ' 23:59:59');
            $url_search = 'from=' . date('d/m/Y', ($now - (365 * 24 * 60 * 60)));
            $url_search .= '&to=' . date('d/m/Y');

            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = explode("/", $_GET['from']);
                $to = explode("/", $_GET['to']);
                if (count($from) == 3 && count($to) == 3) {
                    $values[2] = $from[2] . '-' . Util::AddLeadingZeros($from[1], 2) . '-' . Util::AddLeadingZeros($from[0], 2) . ' 00:00:00';
                    $values[3] = $to[2] . '-' . Util::AddLeadingZeros($to[1], 2) . '-' . Util::AddLeadingZeros($to[0], 2) . ' 23:59:59';
                    $url_search = 'from=' . $_GET['from'];
                    $url_search .= '&to=' . $_GET['to'];
                }
            }

            $sql = "select o.id,o.plan,o.tenure,o.expires,o.remarks,o.state,p.status,o.method,o.transaction_id as gateway_ref,p.amount,p.transaction_id,o.creation_date,o.last_changed from " . PrepaidOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.status = ? and o.user = ? and o.creation_date between ? and ?";
            $csql = "select count(*) from " . PrepaidOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.status = ? and o.user = ? and o.creation_date between ? and ?";
            $query = $pm->getQueryBuilder('PrepaidOrder');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/prepaid/payment-history?' . $url_search . '&');
            $pagination->setPages();
            $orders = $query->executeQuery($sql . ' order by o.creation_date desc', $values, (($index - 1) * $size), $size);
            $_GET['orders'] = $orders;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            echo $e;
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoInsert() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'plan');
            $assertProperties->addProperty($_POST, 'start');
            $assertProperties->addProperty($_POST, 'tenure');
            $assertProperties->addProperty($_POST, 'method');
            $assertProperties->addProperty($_POST, 'agent');
            $assertProperties->addProperty($_POST, 'name');
            $assertProperties->addProperty($_POST, 'password');
            $assertProperties->addProperty($_POST, 'phone');
            $assertProperties->addProperty($_POST, 'email');
            $assertProperties->addProperty($_POST, 'address');
            $assertProperties->addProperty($_POST, 'city');
            $assertProperties->addProperty($_POST, 'remarks');
            $assertProperties->addProperty($_POST, 'user');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $pm->beginTransaction();
            $prepaidUser = new PrepaidUser();
            if (strlen($_POST['user']) == 0) {
                $prepaidUser->setValue('agent', $_POST['agent']);
                $prepaidUser->setValue('name', $_POST['name']);
                $prepaidUser->setValue('phone', $_POST['phone']);
                $prepaidUser->setValue('email', $_POST['email']);
                $prepaidUser->setValue('address', $_POST['address']);
                $prepaidUser->setValue('city', $_POST['city']);
                if (UserService::GetUser()->getValue('type') == 'Admin') {
                    $prepaidUser->setValue('state', 1);
                } else {
                    $prepaidUser->setValue('state', 0);
                }
                $pm->save($prepaidUser);

                $prepaidUserAccountNumber = new PrepaidUserAccountNumber();
                $prepaidUserAccountNumber->setValue('prepaid_user', $prepaidUser->getValue('id'));
                $prepaidUserAccountNumber->setValue('account_number', PrepaidUserService::GetNewAccountNumber());
                $pm->save($prepaidUserAccountNumber);

                $user = new User();
                $user->setValue('type', 'Prepaid');
                $user->setValue('password', md5($_POST['password'] . $user->getValue('id') . 'genesisville'));
                $user->setValue('username', $prepaidUserAccountNumber->getValue('account_number'));
                $pm->save($user);
            } else {
                $prepaidUser = $pm->getObjectById('PrepaidUser', $_POST['user']);
            }

            $order = new PrepaidOrder();
            $order->setValue('user', $prepaidUser->getValue('id'));
            $order->setValue('tenure', $_POST['tenure']);
            $order->setValue('plan', $_POST['plan']);
            if (UserService::GetUser()->getValue('type') == 'Admin') {
                $order->setValue('state', 1);
            } else {
                $order->setValue('station', $pm->getObjectByColumn('StationUser', 'user', UserService::GetUser()->getValue('username'))->getValue('station'));
                $order->setValue('state', 0);
            }
            $pm->save($prepaidUser);
            $order->setValue('status', 3);
            $start = $_POST['start'];
            if (UserService::GetUser()->getValue('type') != 'Admin') {
                $_GET['user'] = $_POST['user'];
                $_GET['tenure'] = $_POST['tenure'];
                $_GET['plan'] = $_POST['plan'];
                $info = json_decode(self::GetStartDate(), true);
                $start = $info['start'];
            }
            $begin_parts = explode('/', $start);
            $begin = mktime(0, 0, 0, $begin_parts[1], $begin_parts[0], $begin_parts[2]);
            $expires = $begin + (60 * 60 * 24 * 30 * $_POST['tenure']);
            $order->setValue('expires', date('Y-m-d', $expires));
            $order->setValue('posted_by', UserService::GetUser()->getValue('username'));
            $order->setValue('remarks', $_POST['remarks']);
            $pm->save($order);

            $plan = $pm->getObjectByColumn('PrepaidPlan', 'name', $_POST['plan']);

            $payment = new Payment();
            $payment->setValue('amount', $plan->getValue('amount') * $_POST['tenure']);
            $payment->setValue('method', $_POST['method']);
            $payment->setValue('related_to', $order->getValue('id'));
            $payment->setValue('status', 1);
            $payment->setValue('type', 'PrepaidUser');
            $pm->save($payment);

            $pm->commit();

            $sql = "select o.id,o.plan,o.tenure,o.expires,a.account_number,o.posted_by,o.remarks,o.state,p.status,o.method,o.transaction_id as gateway_ref,p.amount,p.transaction_id,o.creation_date,o.last_changed from " . PrepaidOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p inner join " . PrepaidUserAccountNumber::GetDSN() . " as a on o.id = p.related_to and o.user = a.prepaid_user where a.prepaid_user = ? order by o.creation_date desc";
            $query = $pm->getQueryBuilder('PrepaidOrder');

            $_GET['orders'] = $query->executeQuery($sql, array($prepaidUser->getValue('id')), 0, 1);

            if (strlen($_POST['user']) == 0) {
                SMSService::SendPrepaidUserRegistration($_GET['orders'][0]->getValue('account_number'), trim($_POST['password']), $_POST['phone']);
            }
            if (UserService::GetUser()->getValue('type') == 'Admin') {
                SMSService::SendPrepaidUserActivation($_GET['orders'][0]->getValue('plan'), Date::convertFromMySqlDate($_GET['orders'][0]->getValue('expires')), ($_GET['orders'][0]->getValue('tenure') * 30), $prepaidUser->getValue('phone'), $_GET['orders'][0]->getValue('account_number'));
            } else {
                SMSService::SendSMS(str_replace(" ", "+", "Hi, You have a pending Prepaid User Registration"), "2348064363747", "GenesisISP", true);
            }
        } catch (Exception $e) {
            $pm->rollBack();
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function PlaceNewSubscription() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'plan');
            $assertProperties->addProperty($_POST, 'tenure');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $pm->beginTransaction();

            $values = array(UserService::GetUser()->getValue('username'));
            $sql = "select p.id,p.phone from " . PrepaidUser::GetDSN() . " as p inner join " . PrepaidUserAccountNumber::GetDSN() . " as a inner join " . User::GetDSN() . " as u on p.id = a.prepaid_user and a.account_number = u.username where u.username = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $users = $query->executeQuery($sql, $values, 0, 1);

            $order = new PrepaidOrder();
            $order->setValue('user', $users[0]->getValue('id'));
            $order->setValue('tenure', $_POST['tenure']);
            $order->setValue('plan', $_POST['plan']);
            $order->setValue('state', 0);
            $order->setValue('status', 0);

            $_GET['user'] = $users[0]->getValue('phone');
            $_GET['tenure'] = $_POST['tenure'];
            $info = json_decode(self::GetStartDate(), true);
            $start = $info['start'];

            $begin_parts = explode('/', $start);
            $begin = mktime(0, 0, 0, $begin_parts[1], $begin_parts[0], $begin_parts[2]);
            $expires = $begin + (60 * 60 * 24 * 30 * $_POST['tenure']);
            $order->setValue('expires', date('Y-m-d', $expires));
            $order->setValue('posted_by', UserService::GetUser()->getValue('username'));


            $plan = $pm->getObjectByColumn('PrepaidPlan', 'name', $order->getValue('plan'));
            $amount = $order->getValue('tenure') * $plan->getValue('amount');
            $order->setValue('amount', $amount);

            $paymentGatewayTransaction = new PaymentGatewayTransaction();
            $paymentGatewayTransaction->setValue('source', "PrepaidOrder");
            $paymentGatewayTransaction->setValue('amount', $amount);
            $order->setValue('transaction_id', $paymentGatewayTransaction->getValue('id'));

            $pm->save($paymentGatewayTransaction);
            $pm->save($order);

            $pm->commit();

            $_GET['order'] = $order;
            $_GET['amount'] = $amount;

            if ($_POST['method'] == '1') {
                $_GET['view'] = 'payment-form.php';
            } else {
                $_GET['view'] = 'bank-details.php';
            }
        } catch (Exception $e) {
            $pm->rollBack();
            $_GET['error'] = $e->getMessage();
            $_GET['view'] = 'new-subcription-order.php';
        }
    }

    public static function SubscriptionNotify($paymentGatewayTransaction, $transaction) {
        try {
            if (strlen($paymentGatewayTransaction->getValue('response_code')) > 0) {
                throw new Exception("Already completed transaction");
            }

            $paymentGatewayTransaction->setValue('merchant_reference', $transaction['MerchantReference']);
            $paymentGatewayTransaction->setValue('response_code', $transaction['ResponseCode']);
            $paymentGatewayTransaction->setValue('response_description', $transaction['ResponseDescription']);

            $pm = PersistenceManager::NewPersistenceManager();
            $pm->beginTransaction();

            $order = $pm->getObjectByColumn('PrepaidOrder', 'transaction_id', $paymentGatewayTransaction->getValue('id'));

            if (strcasecmp($transaction['ResponseCode'], "00") == 0) {
                if (strcasecmp(number_format($transaction['Amount'], 2), number_format($paymentGatewayTransaction->getValue('amount') * 100, 2)) == 0) {
                    $paymentGatewayTransaction->setValue('status', 3);

                    $order->setValue('status', 3);

                    $payment = new Payment();
                    $payment->setValue('amount', $order->getValue('amount'));
                    $payment->setValue('status', 1);
                    $payment->setValue('type', 'Prepaid');
                    $payment->setValue('method', 'ePayment');
                    $payment->setValue('related_to', $order->getValue('id'));
                    $pm->save($payment);

                    $prepaidUser = $pm->getObjectById('PrepaidUser', $order->getValue('user'));
                    $prepaidUserAccountNumber = $pm->getObjectByColumn('PrepaidUserAccountNumber', 'prepaid_user', $order->getValue('user'));
                    SMSService::SendPrepaidRecurringPayment($order->getValue('plan'), Date::convertFromMySqlDate($order->getValue('expires')), ($order->getValue('tenure') * 30), $order->getValue('amount'), $prepaidUser->getValue('phone'), $prepaidUserAccountNumber->getValue('account_number'));

                    $_GET['view'] = 'payment-accepted.php';
                } else {
                    $order->setValue('status', 2);
                    $_GET['view'] = 'payment-error.php';
                }
            } else {
                $_GET['view'] = 'payment-rejected.php';
                $order->setValue('status', 2);
            }

            $pm->save($paymentGatewayTransaction);
            $pm->save($order);

            $pm->commit();
            header("location: " . CONTEXT_PATH . '/prepaid/payment-history');
            $_GET['transaction'] = $transaction;
            $_GET['order'] = $order;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            $_GET['view'] = 'error.php';
            MailService::SendError("Prepaid - Recurring ePayment", $e->getMessage());
            header("location: " . CONTEXT_PATH . '/prepaid/payment-history');
        }
    }

    public static function Accept() {
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_GET, 'id');
            $assertProperties->assert();

            $pm = PersistenceManager::NewPersistenceManager();
            $order = $pm->getObjectById('PrepaidOrder', $_GET['id']);
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

            $pm->commit();

            $prepaidUser = $pm->getObjectById('PrepaidUser', $order->getValue('user'));
            $prepaidUserAccountNumber = $pm->getObjectByColumn('PrepaidUserAccountNumber', 'prepaid_user', $order->getValue('user'));
            SMSService::SendPrepaidRecurringPayment($order->getValue('plan'), Date::convertFromMySqlDate($order->getValue('expires')), ($order->getValue('tenure') * 30), $order->getValue('amount'), $prepaidUser->getValue('phone'), $prepaidUserAccountNumber->getValue('account_number'));

            header('Location: ' . CONTEXT_PATH . '/backend/prepaid/orders');
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            $_GET['view'] = 'error.php';
            MailService::SendError("Prepaid - Recurring ePayment", $e->getMessage());
        }
    }

    public static function DoUpdate() {
        try {

            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'data');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $data = json_decode(stripslashes($_POST['data']), true);

            $orders = new PersistableListObject(null, null);

            foreach ($data as $id => $row) {

                $assertProperties->addProperty($row, 'state');
                $assertProperties->addProperty($row, 'expires');
                $assertProperties->assert();

                $order = $pm->getObjectById('PrepaidOrder', $id);

                if ($row['state'] == 1) {
                    $order->setValue('state', $row['state']);
                    $order->setValue('expires', Date::convertToMySqlDate($row['expires']));

                    $pm->save($order);

                    $sql = "select o.id,o.plan,o.tenure,o.expires,a.account_number,o.posted_by,o.remarks,o.state,p.status,o.method,o.transaction_id as gateway_ref,p.amount,p.transaction_id,o.creation_date,o.last_changed,u.phone from " . PrepaidOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p inner join " . PrepaidUserAccountNumber::GetDSN() . " as a inner join " . PrepaidUser::GetDSN() . " as u on o.id = p.related_to and o.user = a.prepaid_user and a.prepaid_user = u.id where o.id = ? order by o.creation_date desc";
                    $query = $pm->getQueryBuilder('PrepaidOrder');
                    $_orders = $query->executeQuery($sql, array($order->getValue('id')), 0, 1);
                    $orders->add($_orders[0]);

                    SMSService::SendPrepaidUserActivation($_orders[0]->getValue('plan'), Date::convertFromMySqlDate($_orders[0]->getValue('expires')), ($_orders[0]->getValue('tenure') * 30), $_orders[0]->getValue('phone'), $_orders[0]->getValue('account_number'));
                } else {
                    $orders->add($order);
                }
            }

            $_GET['orders'] = $orders;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetSummary() {
        try {
            $values = array(1, 3, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');

            $sql = "select sum(p.amount) as amount from " . PrepaidOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where p.status = ? and o.status = ? and o.creation_date between ? and ?";

            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = explode("/", $_GET['from']);
                $to = explode("/", $_GET['to']);
                if (count($from) == 3 && count($to) == 3) {
                    $values[2] = $from[2] . '-' . Util::AddLeadingZeros($from[1], 2) . '-' . Util::AddLeadingZeros($from[0], 2) . ' 00:00:00';
                    $values[3] = $to[2] . '-' . Util::AddLeadingZeros($to[1], 2) . '-' . Util::AddLeadingZeros($to[0], 2) . ' 23:59:59';
                }
            }

            if (isset($_GET['prepaid']) && $_GET['prepaid'] != 'all') {
                $sql .= " and o.prepaid = ?";
                $values[] = urldecode($_GET['prepaid']);
            }

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidOrder');
            $orders = $query->executeQuery($sql, $values, 0, 1);
            $_GET['summary'] = $orders[0];
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetStationSummary() {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $station = $pm->getObjectByColumn('StationUser', 'user', UserService::GetUser()->getValue('username'))->getValue('station');

            $values = array($station, 1, 3, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');

            $sql = "select sum(p.amount) as amount from " . PrepaidOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.station = ? and p.status = ? and o.status = ? and o.creation_date between ? and ?";

            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = explode("/", $_GET['from']);
                $to = explode("/", $_GET['to']);
                if (count($from) == 3 && count($to) == 3) {
                    $values[3] = $from[2] . '-' . Util::AddLeadingZeros($from[1], 2) . '-' . Util::AddLeadingZeros($from[0], 2) . ' 00:00:00';
                    $values[4] = $to[2] . '-' . Util::AddLeadingZeros($to[1], 2) . '-' . Util::AddLeadingZeros($to[0], 2) . ' 23:59:59';
                }
            }

            if (isset($_GET['prepaid']) && $_GET['prepaid'] != 'all') {
                $sql .= " and o.prepaid = ?";
                $values[] = urldecode($_GET['prepaid']);
            }

            $query = $pm->getQueryBuilder('PrepaidOrder');
            $orders = $query->executeQuery($sql, $values, 0, 1);
            $_GET['summary'] = $orders[0];
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetStartDate() {
        try {
            $values = array($_GET['plan']);
            $sql = "select amount from " . PrepaidPlan::GetDSN() . " where name = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidPlan');
            $plans = $query->executeQuery($sql, $values, 0, 1);
            $info['amount'] = number_format($plans[0]->getValue('amount') * $_GET['tenure'], 2);
            $info['start'] = date('d/m/Y');
            if (strlen($_GET['user']) > 0) {
                $sql = "select expires from " . PrepaidOrder::GetDSN() . " where user = ? and state = ? and status = ? order by creation_date desc";
                $query = $pm->getQueryBuilder('PrepaidOrder');
                $orders = $query->executeQuery($sql, array($_GET['user'], 1, 3), 0, 1);
                if ($orders->count() > 0) {
                    $date = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                    $paths = explode('-', $orders[0]->getValue('expires'));
                    $old_date = mktime(0, 0, 0, $paths[1], $paths[2], $paths[0]);
                    if ($date < $old_date) {
                        $info['start'] = date('d/m/Y', $old_date + (60 * 60 * 24));
                    }
                }
            }
            return json_encode($info);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

}

?>
