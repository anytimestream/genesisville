<?php

class TicketService {

    public static function GetStationTickets() {
        try {
            $planGroups = array();
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Plan');
            $sql = 'select distinct p._group from ' . Plan::GetDSN() . ' as p Inner Join ' . StationPlan::GetDSN() . ' as s inner join ' . StationUser::GetDSN() . ' as u on p.name = s.plan and s.station = u.station where u.user = ? order by p._group';
            $plans = $query->executeQuery($sql, array(UserService::GetUser()->getValue('username')), 0, 1000);
            for ($i = 0; $i < $plans->count(); $i++) {
                $sql = 'select p.name,p.amount from ' . Plan::GetDSN() . ' as p Inner Join ' . StationPlan::GetDSN() . ' as s inner join ' . StationUser::GetDSN() . ' as u on p.name = s.plan and s.station = u.station where p._group = ? and u.user = ? order by p._group';
                $planGroups[] = $query->executeQuery($sql, array($plans[$i]->getValue('_group'), UserService::GetUser()->getValue('username')), 0, 1000);
            }
            $_GET['plan-groups'] = $planGroups;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetHotelTickets() {
        try {
            $planGroups = array();
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Plan');
            $sql = 'select distinct p._group from ' . Plan::GetDSN() . ' as p Inner Join ' . HotelPlan::GetDSN() . ' as h inner join ' . HotelUser::GetDSN() . ' as u on p.name = h.plan and h.hotel = u.hotel where u.user = ? order by p.name';
            $plans = $query->executeQuery($sql, array(UserService::GetUser()->getValue('username')), 0, 1000);
            for ($i = 0; $i < $plans->count(); $i++) {
                $sql = 'select p.name from ' . Plan::GetDSN() . ' as p Inner Join ' . HotelPlan::GetDSN() . ' as h inner join ' . HotelUser::GetDSN() . ' as u on p.name = h.plan and h.hotel = u.hotel where p._group = ? and u.user = ? order by p.name';
                $planGroups[] = $query->executeQuery($sql, array($plans[$i]->getValue('_group'), UserService::GetUser()->getValue('username')), 0, 1000);
            }
            $_GET['plan-groups'] = $planGroups;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function AssertCard() {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Card');
            $row = $query->execute("select count(*) from " . Card::GetDSN() . " where plan = ? and status = ?", array($_GET['plan'], 0))->fetch();
            if ($row[0] == 0) {
                return false;
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
        return true;
    }

    public static function NewOrder() {
        try {
            $phone = '';
            if (isset($_POST['phone'])) {
                $phone = $_POST['phone'];
            }
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Plan');
            $plans = $query->executeQuery("select name,description,amount from " . Plan::GetDSN() . " where name = ?", array($_GET['plan']), 0, 1);
            $_GET['plan'] = $plans[0];
            $_GET['phone'] = $phone;
            $_GET['token'] = uniqid(rand(), true);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function PlaceStationOrder() {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Plan');
            $plans = $query->executeQuery("select p.name,p.description,s.contract_value,p.amount,p.uptime,p.validity,s.station from " . Plan::GetDSN() . " as p Inner Join " . StationPlan::GetDSN() . " as s inner join " . StationUser::GetDSN() . " as u on p.name = s.plan and s.station = u.station where p.name = ? and u.user = ?", array($_GET['plan'], UserService::GetUser()->getValue('username')), 0, 1);
            if ($plans->count() == 0) {
                header('Location: ' . CONTEXT_PATH . '/station/tickets?plan=' . $_GET['plan']);
            }
            $plan = $plans[0];

            $pm->beginTransaction();

            $order = new StationOrder();
            $order->setValue('contract_value', $plan->getValue('contract_value'));
            $order->setValue('plan', $plan->getValue('name'));
            $order->setValue('station', $plan->getValue('station'));
            $order->setValue('phone', $_POST['phone']);
            $order->setValue('token', $_POST['token']);
            $order->setValue('user', UserService::GetUser()->getValue('username'));
            $pm->save($order);

            $payment = New Payment();
            $payment->setValue('amount', $plan->getValue('amount'));
            $payment->setValue('method', "Cash");
            $payment->setValue('type', "Station");
            $payment->setValue('related_to', $order->getValue('id'));
            $payment->setValue('status', 1);
            $pm->save($payment);

            $query = $pm->getQueryBuilder('Card');
            $cards = $query->executeQuery("select * from " . Card::GetDSN() . " where plan = ? and status = ?", array($plan->getValue('name'), 0), 0, 1);
            $card = $cards[0];
            $card->setValue('status', 1);
            $card->setValue('transaction_id', $pm->getObjectById('Payment', $payment->getValue('id'))->getValue('transaction_id'));
            $pm->save($card);

            $pm->commit();

            PhoneNumberService::DoInsert($order->getValue('phone'), 'Station', $plan->getValue('station'));

            if ($_POST['method'] == 'SMS') {
                SMSService::SendStationTicketByOrderId($order->getValue('id'), true);
            }
        } catch (Exception $e) {
            $pm->rollBack();
            if (strpos($e, '1062 Duplicate entry') != false) {
                $_GET['token-error'] = "Double Order Detected";
            } else {
                $_GET['error'] = $e->getMessage();
            }
        }
    }

    public static function PlaceHotelOrder() {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Plan');
            $plans = $query->executeQuery("select p.name,p.description,h.point,p.amount,p.uptime,p.validity,h.hotel from " . Plan::GetDSN() . " as p Inner Join " . HotelPlan::GetDSN() . " as h inner join " . HotelUser::GetDSN() . " as u on p.name = h.plan and h.hotel = u.hotel where p.name = ? and u.user = ?", array($_GET['plan'], UserService::GetUser()->getValue('username')), 0, 1);
            if ($plans->count() == 0) {
                header('Location: ' . CONTEXT_PATH . '/hotel/tickets?plan=' . $_GET['plan']);
            }
            $plan = $plans[0];

            $pm->beginTransaction();

            $order = new HotelOrder();
            $order->setValue('point', $plan->getValue('point'));
            $order->setValue('plan', $plan->getValue('name'));
            $order->setValue('hotel', $plan->getValue('hotel'));
            $order->setValue('phone', $_POST['phone']);
            $order->setValue('user', UserService::GetUser()->getValue('username'));
            $pm->save($order);

            $payment = New Payment();
            $payment->setValue('amount', $plan->getValue('amount'));
            $payment->setValue('method', "Cash");
            $payment->setValue('type', "Hotel");
            $payment->setValue('related_to', $order->getValue('id'));
            $payment->setValue('status', 1);
            $pm->save($payment);

            $query = $pm->getQueryBuilder('Card');
            $cards = $query->executeQuery("select * from " . Card::GetDSN() . " where plan = ? and status = ?", array($plan->getValue('name'), 0), 0, 1);
            $card = $cards[0];
            $card->setValue('status', 1);
            $card->setValue('transaction_id', $pm->getObjectById('Payment', $payment->getValue('id'))->getValue('transaction_id'));
            $pm->save($card);

            $pm->commit();

            PhoneNumberService::DoInsert($order->getValue('phone'), 'Hotel', $plan->getValue('hotel'));

            if ($_POST['method'] == 'SMS') {
                SMSService::SendHotelTicketByOrderId($order->getValue('id'), true);
            }
        } catch (Exception $e) {
            $pm->rollBack();
            $_GET['error'] = $e->getMessage();
        }
    }

}

?>
