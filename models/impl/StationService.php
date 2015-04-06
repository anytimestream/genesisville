<?php

class StationService {

    public static function GetStations() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array(0);
            $csql = "select count(*) from " . Station::GetDSN() . " where deleted = ?";
            $sql = "select * from " . Station::GetDSN() . " where deleted = ? order by name";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Station');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/station/stations?');
            $pagination->setPages();
            $stations = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['stations'] = $stations;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetReport() {
        try {
            $values = array();

            $begin_parts = explode('/', $_POST['begin']);
            $end_parts = explode('/', $_POST['end']);
            $values[] = $begin_parts[2] . '-' . $begin_parts[1] . '-' . $begin_parts[0];
            $values[] = $end_parts[2] . '-' . $end_parts[1] . '-' . $end_parts[0];
            $values[] = $_POST['station'];

            $sql = "select count(o.id) as total, sum(o.contract_value) as amount, p.creation_date from " . StationOrder::GetDSN() . " as o Inner Join " . Station::GetDSN() . " as s inner join ".Payment::GetDSN()." as p on o.station = s.name and o.id = p.related_to where p.creation_date between ? and ? and s.name = ? group by DATE_FORMAT(o.creation_date, '%Y%m%d') order by o.creation_date";

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('StationOrder');
            $orders = $query->executeQuery($sql, $values, 0, 1000000);
            
            $sql = "select sum(p.amount) as amount, p.creation_date from " . StationRemittance::GetDSN() . " as r inner join " . Payment::GetDSN() . " as p on r.id = p.related_to where p.creation_date between ? and ? and r.station = ? and p.status = ? group by DATE_FORMAT(p.creation_date, '%Y%m%d') order by p.creation_date";

            $values[] = 1;

            $query = $pm->getQueryBuilder('Payment');
            $payments = $query->executeQuery($sql, $values, 0, 1000000);

            $values = array();
            $values[] = $end_parts[2] . '-' . $end_parts[1] . '-' . $end_parts[0];
            $values[] = $_POST['station'];
            $sql = "select count(o.id) as total, sum(o.contract_value) as amount, p.creation_date from " . StationOrder::GetDSN() . " as o Inner Join " . Station::GetDSN() . " as s inner join ".Payment::GetDSN()." as p on o.station = s.name and o.id = p.related_to where o.creation_date <= ? and s.name = ?";
            
            $query = $pm->getQueryBuilder('StationOrder');
            $orders2 = $query->executeQuery($sql, $values, 0, 1000000);

            $values[] = 1;
            $sql = "select sum(p.amount) as amount from " . StationRemittance::GetDSN() . " as r inner join " . Payment::GetDSN() . " as p on r.id = p.related_to where p.creation_date <= ? and r.station = ? and p.status = ?";
            $query = $pm->getQueryBuilder('Payment');
            $payments2 = $query->executeQuery($sql, $values, 0, 1);

            $start = mktime(0, 0, 0, $begin_parts[1], $begin_parts[0], $begin_parts[2]);
            $start -= 24 * 60 * 60;
            $end = mktime(0, 0, 0, $end_parts[1], $end_parts[0], $end_parts[2]);
            
            $_GET['start'] = $start;
            $_GET['end'] = $end;

            $_GET['orders'] = $orders;
            $_GET['orders2'] = $orders2;

            $_GET['payments'] = $payments;
            $_GET['payments2'] = $payments2;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function CanAcceptPrepaid() {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Station');
            $sql = "select s.accept_prepaid_order from ".Station::GetDSN()." as s inner join ".StationUser::GetDSN()." as u on s.name = u.station where u.user = ?";
            $stations = $query->executeQuery($sql, array(UserService::GetUser()->getValue('username')), 0, 1);
            if($stations[0]->getValue('accept_prepaid_order') == 1){
                return true;
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function GetStationReport() {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $station = $pm->getObjectByColumn('StationUser', 'user', UserService::GetUser()->getValue('username'))->getValue('station');
            $values = array();

            $begin_parts = explode('/', $_POST['begin']);
            $end_parts = explode('/', $_POST['end']);
            $values[] = $begin_parts[2] . '-' . $begin_parts[1] . '-' . $begin_parts[0];
            $values[] = $end_parts[2] . '-' . $end_parts[1] . '-' . $end_parts[0];
            $values[] = $station;

            $sql = "select count(o.id) as total, sum(o.contract_value) as amount, p.creation_date from " . StationOrder::GetDSN() . " as o Inner Join " . Station::GetDSN() . " as s inner join ".Payment::GetDSN()." as p on o.station = s.name and o.id = p.related_to where p.creation_date between ? and ? and s.name = ? group by DATE_FORMAT(o.creation_date, '%Y%m%d') order by o.creation_date";

            $query = $pm->getQueryBuilder('StationOrder');
            $orders = $query->executeQuery($sql, $values, 0, 1000000);

            $values = array();
            $values[] = $end_parts[2] . '-' . $end_parts[1] . '-' . $end_parts[0];
            $values[] = $station;
            $sql = "select count(o.id) as total, sum(o.contract_value) as amount, p.creation_date from " . StationOrder::GetDSN() . " as o Inner Join " . Station::GetDSN() . " as s inner join ".Payment::GetDSN()." as p on o.station = s.name and o.id = p.related_to where p.creation_date <= ? and s.name = ?";
            $orders2 = $query->executeQuery($sql, $values, 0, 1000000);

            $sql = "select sum(p.amount) as amount, p.creation_date from " . StationRemittance::GetDSN() . " as r inner join " . Payment::GetDSN() . " as p on r.id = p.related_to where p.creation_date <= ? and r.station = ? and p.status = ? group by p.creation_date order by p.creation_date";

            $values[] = 1;

            $query = $pm->getQueryBuilder('Payment');
            $payments = $query->executeQuery($sql, $values, 0, 1000000);

            $sql = "select sum(p.amount) as amount from " . StationRemittance::GetDSN() . " as r inner join " . Payment::GetDSN() . " as p on r.id = p.related_to where p.creation_date <= ? and r.station = ? and p.status = ?";

            $payments2 = $query->executeQuery($sql, $values, 0, 1);

            $start = mktime(0, 0, 0, $begin_parts[1], $begin_parts[0], $begin_parts[2]);
            $start -= 24 * 60 * 60;
            $end = mktime(0, 0, 0, $end_parts[1], $end_parts[0], $end_parts[2]);
            
            $_GET['start'] = $start;
            $_GET['end'] = $end;

            $_GET['orders'] = $orders;
            $_GET['orders2'] = $orders2;

            $_GET['payments'] = $payments;
            $_GET['payments2'] = $payments2;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function GetStationReportBalance() {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $station = $pm->getObjectByColumn('StationUser', 'user', UserService::GetUser()->getValue('username'))->getValue('station');
            $values = array();

            $values[] = $station;

            $sql = "select sum(o.contract_value) as contract_value from " . StationOrder::GetDSN() . " as o Inner Join " . Station::GetDSN() . " as s on o.station = s.name where s.name = ?";
            $query = $pm->getQueryBuilder('StationOrder');
            $orders = $query->executeQuery($sql, $values, 0, 1);
            $balance = $orders[0]->getValue('contract_value');
            
            $values[] = 1;
            $sql = "select sum(p.amount) as amount from " . Payment::GetDSN() . " as p Inner Join " . Station::GetDSN() . " as s inner join ".  StationRemittance::GetDSN()." as r on p.related_to = r.id and r.station = s.name where s.name = ? and p.status = ?";
            $query = $pm->getQueryBuilder('Payment');
            $orders = $query->executeQuery($sql, $values, 0, 1);
            $balance = $orders[0]->getValue('amount') - $balance;

            $_GET['balance'] = number_format($balance, 0, '.', '');
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetReportByDate($date) {
        $report['tickets'] = 0;
        $report['amount'] = 0;
        for ($i = 0; $i < $_GET['orders']->count(); $i++) {
            if ($_GET['orders'][$i]->getValue('creation_date') == $date) {
                $report['tickets'] = $_GET['orders'][$i]->getValue('total');
                $report['amount'] = $_GET['orders'][$i]->getValue('amount');
            }
        }
        return $report;
    }

    public static function GetRemittanceByDate($date) {
        for ($i = 0; $i < $_GET['payments']->count(); $i++) {
            if ($_GET['payments'][$i]->getValue('creation_date') == $date) {
                return $_GET['payments'][$i]->getValue('amount');
            }
        }
        return 0;
    }

    public static function GetTotalTicketsReport() {
        $tickets  = 0;
        for ($i = 0; $i < $_GET['orders']->count(); $i++) {
            $tickets += $_GET['orders'][$i]->getValue('total');
        }
        return $tickets;
    }
    
    public static function GetTotalAmountReport() {
        $amount  = 0;
        for ($i = 0; $i < $_GET['orders']->count(); $i++) {
            $amount += $_GET['orders'][$i]->getValue('amount');
        }
        return $amount;
    }
    
    public static function GetTotalRemitReport() {
        $remittance  = 0;
        for ($i = 0; $i < $_GET['payments']->count(); $i++) {
            $remittance += $_GET['payments'][$i]->getValue('amount');
        }
        return $remittance;
    }
    
    public static function GetStationNames() {
        try {
            $values = array(0);
            $sql = "select name from " . Station::GetDSN() . " where deleted = ? order by name";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Station');
            $stations = $query->executeQuery($sql, $values, 0, 100000);
            $_GET['stations'] = $stations;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoInsert() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'name');
            $assertProperties->addProperty($_POST, 'address');
            $assertProperties->addProperty($_POST, 'phone');
            $assertProperties->addProperty($_POST, 'sms');
            $assertProperties->addProperty($_POST, 'sms_sender_name');
            $assertProperties->addProperty($_POST, 'accept_prepaid_order');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $station = new Station();
            $station->setValue('name', $_POST['name']);
            $station->setValue('address', $_POST['address']);
            $station->setValue('phone', $_POST['phone']);
            $station->setValue('sms', $_POST['sms']);
            $station->setValue('sms_sender_name', $_POST['sms_sender_name']);
            $station->setValue('accept_prepaid_order', $_POST['accept_prepaid_order']);
            $pm->save($station);

            $stations = new PersistableListObject(null, null);
            $stations->add($station);
            $_GET['stations'] = $stations;
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

            $stations = new PersistableListObject(null, null);

            foreach ($data as $id => $row) {

                $assertProperties->addProperty($row, 'name');
                $assertProperties->addProperty($row, 'address');
                $assertProperties->addProperty($row, 'phone');
                $assertProperties->addProperty($row, 'sms');
                $assertProperties->addProperty($row, 'sms_sender_name');
                $assertProperties->addProperty($row, 'disabled');
                $assertProperties->addProperty($row, 'accept_prepaid_order');
                $assertProperties->assert();

                $station = $pm->getObjectById('Station', $id);

                $station->setValue('name', $row['name']);
                $station->setValue('address', $row['address']);
                $station->setValue('phone', $row['phone']);
                $station->setValue('sms', $row['sms']);
                $station->setValue('sms_sender_name', $row['sms_sender_name']);
                $station->setValue('disabled', $row['disabled']);
                $station->setValue('accept_prepaid_order', $row['accept_prepaid_order']);

                $pm->save($station);

                $stations->add($station);
            }

            $_GET['stations'] = $stations;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
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
                $station = $pm->getObjectById('Station', $row);
                $station->setValue('deleted', 1);
                $pm->save($station);
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

}

?>
