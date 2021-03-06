<?php

class PCOrderService {

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

            $sql = "select o.id,o.plan,o.tenure,o.expires,a.account_number,o.posted_by,o.remarks,o.state,p.status,o.method,o.transaction_id as gateway_ref,p.amount,p.transaction_id,o.creation_date,o.last_changed from " . PCOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p inner join " . PCUserAccountNumber::GetDSN() . " as a on o.id = p.related_to and o.user = a.pc_user where o.status = ? and o.creation_date between ? and ?";
            $csql = "select count(*) from " . PCOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.status = ? and o.creation_date between ? and ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PCOrder');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/plantation-city/orders?' . $url_search . '&');
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

            $sql = "select o.id,o.plan,o.tenure,o.expires,a.account_number,o.posted_by,o.remarks,o.state,p.status,o.method,o.transaction_id as gateway_ref,p.amount,p.transaction_id,o.creation_date,o.last_changed from " . PCOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p inner join " . PCUserAccountNumber::GetDSN() . " as a on o.id = p.related_to and o.user = a.prepaid_user where o.status = ? and o.station = ? and o.creation_date between ? and ?";
            $csql = "select count(*) from " . PCOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.status = ? and o.station = ? and o.creation_date between ? and ?";
            $query = $pm->getQueryBuilder('PCOrder');
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
            $query = $pm->getQueryBuilder('PCOrder');
            $sql = "select o.id,o.plan,o.tenure,o.expires,a.account_number,o.posted_by,o.remarks,o.state,p.status,o.method,o.transaction_id as gateway_ref,p.amount,p.transaction_id,o.creation_date,o.last_changed from " . PCOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p inner join " . PCUserAccountNumber::GetDSN() . " as a on o.id = p.related_to and o.user = a.prepaid_user where o.id = ?";
            $orders = $query->executeQuery($sql, array($id), 0, 1);
            if ($orders->count() == 0) {
                throw new Exception("Invalid Order");
            }
            $_GET['order'] = $orders[0];
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetPCOrders() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }

            $values = array(UserService::GetUser()->getValue('username'));
            $sql = "select p.id from " . PCUser::GetDSN() . " as p inner join " . PCUserAccountNumber::GetDSN() . " as a inner join " . User::GetDSN() . " as u on p.id = a.prepaid_user and a.account_number = u.username where u.username = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PCUser');
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

            $sql = "select o.id,o.plan,o.tenure,o.expires,o.remarks,o.state,p.status,o.method,o.transaction_id as gateway_ref,p.amount,p.transaction_id,o.creation_date,o.last_changed from " . PCOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.status = ? and o.user = ? and o.creation_date between ? and ?";
            $csql = "select count(*) from " . PCOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.status = ? and o.user = ? and o.creation_date between ? and ?";
            $query = $pm->getQueryBuilder('PCOrder');
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
            $pcUser = new PCUser();
            if (strlen($_POST['user']) == 0) {
                $pcUser->setValue('name', $_POST['name']);
                $pcUser->setValue('phone', $_POST['phone']);
                $pcUser->setValue('email', $_POST['email']);
                $pcUser->setValue('address', $_POST['address']);
                $pcUser->setValue('city', $_POST['city']);
                $pm->save($pcUser);

                $pcUserAccountNumber = new PCUserAccountNumber();
                $pcUserAccountNumber->setValue('pc_user', $pcUser->getValue('id'));
                $pcUserAccountNumber->setValue('account_number', PCUserService::GetNewAccountNumber());
                $pm->save($pcUserAccountNumber);

                $user = new User();
                $user->setValue('type', 'PlantationCity');
                $user->setValue('password', md5($_POST['password'] . $user->getValue('id') . 'genesisville'));
                $user->setValue('username', $pcUserAccountNumber->getValue('account_number'));
                $pm->save($user);
            } else {
                $pcUser = $pm->getObjectById('PCUser', $_POST['user']);
            }

            $order = new PCOrder();
            $order->setValue('user', $pcUser->getValue('id'));
            $order->setValue('tenure', $_POST['tenure']);
            $order->setValue('plan', $_POST['plan']);
            if (UserService::GetUser()->getValue('type') == 'Admin') {
                $order->setValue('state', 1);
            } else {
                $order->setValue('station', $pm->getObjectByColumn('StationUser', 'user', UserService::GetUser()->getValue('username'))->getValue('station'));
                $order->setValue('state', 0);
            }
            $pm->save($pcUser);
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

            $plan = $pm->getObjectByColumn('PCPlan', 'name', $_POST['plan']);

            $payment = new Payment();
            $payment->setValue('amount', $plan->getValue('amount') * $_POST['tenure']);
            $payment->setValue('method', $_POST['method']);
            $payment->setValue('related_to', $order->getValue('id'));
            $payment->setValue('status', 1);
            $payment->setValue('type', 'PlantationCityUser');
            $pm->save($payment);

            $pm->commit();

            $sql = "select o.id,o.plan,o.tenure,o.expires,a.account_number,o.posted_by,o.remarks,o.state,p.status,o.method,o.transaction_id as gateway_ref,p.amount,p.transaction_id,o.creation_date,o.last_changed from " . PCOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p inner join " . PCUserAccountNumber::GetDSN() . " as a on o.id = p.related_to and o.user = a.pc_user where a.pc_user = ? order by o.creation_date desc";
            $query = $pm->getQueryBuilder('PCOrder');

            $_GET['orders'] = $query->executeQuery($sql, array($pcUser->getValue('id')), 0, 1);

            if (strlen($_POST['user']) == 0) {
                SMSService::SendPCUserRegistration($_GET['orders'][0]->getValue('account_number'), trim($_POST['password']), $_POST['phone']);
            }
            if (UserService::GetUser()->getValue('type') == 'Admin') {
                SMSService::SendPCUserActivation($_GET['orders'][0]->getValue('plan'), Date::convertFromMySqlDate($_GET['orders'][0]->getValue('expires')), ($_GET['orders'][0]->getValue('tenure') * 30), $pcUser->getValue('phone'), $_GET['orders'][0]->getValue('account_number'));
            } else {
                SMSService::SendSMS(str_replace(" ", "+", "Hi, You have a pending PC User Registration"), "2348064363747", "GenesisISP", true);
            }
        } catch (Exception $e) {
            $pm->rollBack();
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

            $orders = new PersistableListObject(null, null);

            foreach ($data as $id => $row) {

                $assertProperties->addProperty($row, 'state');
                $assertProperties->addProperty($row, 'expires');
                $assertProperties->assert();

                $order = $pm->getObjectById('PCOrder', $id);

                if ($row['state'] == 1) {
                    $order->setValue('state', $row['state']);
                    $order->setValue('expires', Date::convertToMySqlDate($row['expires']));

                    $pm->save($order);

                    $sql = "select o.id,o.plan,o.tenure,o.expires,a.account_number,o.posted_by,o.remarks,o.state,p.status,o.method,o.transaction_id as gateway_ref,p.amount,p.transaction_id,o.creation_date,o.last_changed,u.phone from " . PCOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p inner join " . PCUserAccountNumber::GetDSN() . " as a inner join " . PCUser::GetDSN() . " as u on o.id = p.related_to and o.user = a.pc_user and a.pc_user = u.id where o.id = ? order by o.creation_date desc";
                    $query = $pm->getQueryBuilder('PCOrder');
                    $_orders = $query->executeQuery($sql, array($order->getValue('id')), 0, 1);
                    $orders->add($_orders[0]);

                    SMSService::SendPCUserActivation($_orders[0]->getValue('plan'), Date::convertFromMySqlDate($_orders[0]->getValue('expires')), ($_orders[0]->getValue('tenure') * 30), $_orders[0]->getValue('phone'), $_orders[0]->getValue('account_number'));
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

            $sql = "select sum(p.amount) as amount from " . PCOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where p.status = ? and o.status = ? and o.creation_date between ? and ?";

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
            $query = $pm->getQueryBuilder('PCOrder');
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

            $sql = "select sum(p.amount) as amount from " . PCOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.station = ? and p.status = ? and o.status = ? and o.creation_date between ? and ?";

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

            $query = $pm->getQueryBuilder('PCOrder');
            $orders = $query->executeQuery($sql, $values, 0, 1);
            $_GET['summary'] = $orders[0];
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetStartDate() {
        try {
            $values = array($_GET['plan']);
            $sql = "select amount from " . PCPlan::GetDSN() . " where name = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PCPlan');
            $plans = $query->executeQuery($sql, $values, 0, 1);
            $info['amount'] = number_format($plans[0]->getValue('amount') * $_GET['tenure'], 2);
            $info['start'] = date('d/m/Y');
            if (strlen($_GET['user']) > 0) {
                $sql = "select expires from " . PCOrder::GetDSN() . " where user = ? and state = ? and status = ? order by creation_date desc";
                $query = $pm->getQueryBuilder('PCOrder');
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
