<?php

class HotelService {

    public static function GetHotels() {
        try {
            $size = 50;
            $index = 1;
            if(isset($_GET['page'])){
                $index = $_GET['page'];
            }
            $values = array(0);
            $csql = "select count(*) from " . Hotel::GetDSN()." where deleted = ?";
            $sql = "select * from " . Hotel::GetDSN() . " where deleted = ? order by name";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Hotel');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/hotel/hotels?');
            $pagination->setPages();
            $hotels = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['hotels'] = $hotels;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function GetHotelNames() {
        try {
            $values = array(0);
            $sql = "select name from " . Hotel::GetDSN() . " where deleted = ? order by name";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Hotel');
            $hotels = $query->executeQuery($sql, $values, 0, 100000);
            $_GET['hotels'] = $hotels;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoInsert() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'name');
            $assertProperties->addProperty($_POST, 'monthly_amount');
            $assertProperties->addProperty($_POST, 'address');
            $assertProperties->addProperty($_POST, 'phone');
            $assertProperties->addProperty($_POST, 'sms');
            $assertProperties->addProperty($_POST, 'sms_sender_name');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $hotel = new Hotel();
            $hotel->setValue('name', $_POST['name']);
            $hotel->setValue('monthly_amount', $_POST['monthly_amount']);
            $hotel->setValue('address', $_POST['address']);
            $hotel->setValue('phone', $_POST['phone']);
            $hotel->setValue('sms', $_POST['sms']);
            $hotel->setValue('sms_sender_name', $_POST['sms_sender_name']);
            $pm->save($hotel);

            $hotels = new PersistableListObject(null, null);
            $hotels->add($hotel);
            $_GET['hotels'] = $hotels;
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

            $hotels = new PersistableListObject(null, null);

            foreach ($data as $id => $row) {

                $assertProperties->addProperty($row, 'name');
                $assertProperties->addProperty($row, 'monthly_amount');
                $assertProperties->addProperty($row, 'address');
                $assertProperties->addProperty($row, 'phone');
                $assertProperties->addProperty($row, 'sms');
                $assertProperties->addProperty($row, 'sms_sender_name');
                $assertProperties->addProperty($row, 'disabled');
                $assertProperties->assert();

                $hotel = $pm->getObjectById('Hotel', $id);

                $hotel->setValue('name', $row['name']);
                $hotel->setValue('monthly_amount', $row['monthly_amount']);
                $hotel->setValue('address', $row['address']);
                $hotel->setValue('phone', $row['phone']);
                $hotel->setValue('sms', $row['sms']);
                $hotel->setValue('sms_sender_name', $row['sms_sender_name']);
                $hotel->setValue('disabled', $row['disabled']);

                $pm->save($hotel);

                $hotels->add($hotel);
            }

            $_GET['hotels'] = $hotels;
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
                $hotel = $pm->getObjectById('Hotel', $row);
                $hotel->setValue('deleted', 1);
                $pm->save($hotel);
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetReport() {
        try {
            $values = array();

            $begin_parts = explode('/', $_POST['begin']);
            $end_parts = explode('/', $_POST['end']);
            $values[] = $begin_parts[2].$begin_parts[1];
            $values[] = $end_parts[2].$end_parts[1];
            $values[] = $_POST['hotel'];

            $sql = "select count(o.id) as tikects, sum(o.point) as points, DATE_FORMAT(o.creation_date, '%m/%Y') as creation_date from " . HotelOrder::GetDSN() . " as o Inner Join " . Hotel::GetDSN() . " as h on o.hotel = h.name where DATE_FORMAT(o.creation_date, '%Y%m') between ? and ? and h.name = ? group by DATE_FORMAT(o.creation_date, '%Y%m') order by o.creation_date";

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('HotelOrder');
            $orders = $query->executeQuery($sql, $values, 0, 1000000);

            $_GET['orders'] = $orders;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function GetHotelMonthlyPoints() {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $hotel = $pm->getObjectByColumn('HotelUser', 'user', UserService::GetUser()->getValue('username'))->getValue('hotel');
            $values = array(date('m/Y'));

            $values[] = $hotel;

            $sql = "select sum(o.point) as points from " . HotelOrder::GetDSN() . " as o Inner Join " . Hotel::GetDSN() . " as h on o.hotel = h.name where DATE_FORMAT(o.creation_date, '%m/%Y') = ? and h.name = ?";
            $query = $pm->getQueryBuilder('HotelOrder');
            $orders = $query->executeQuery($sql, $values, 0, 1);
            
            $_GET['points'] = number_format($orders[0]->getValue('points'));
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function GetHotelReport() {
        try {
            $values = array();
            
            $pm = PersistenceManager::NewPersistenceManager();

            $begin_parts = explode('/', $_POST['begin']);
            $end_parts = explode('/', $_POST['end']);
            $values[] = $begin_parts[2].$begin_parts[1];
            $values[] = $end_parts[2].$end_parts[1];
            $values[] = $pm->getObjectByColumn('HotelUser', 'user', UserService::GetUser()->getValue('username'))->getValue('hotel');

            $sql = "select count(o.id) as tikects, sum(o.point) as points, DATE_FORMAT(o.creation_date, '%m/%Y') as creation_date from " . HotelOrder::GetDSN() . " as o Inner Join " . Hotel::GetDSN() . " as h on o.hotel = h.name where DATE_FORMAT(o.creation_date, '%Y%m') between ? and ? and h.name = ? group by DATE_FORMAT(o.creation_date, '%Y%m') order by o.creation_date";

            $query = $pm->getQueryBuilder('HotelOrder');
            $orders = $query->executeQuery($sql, $values, 0, 1000000);

            $_GET['orders'] = $orders;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
}

?>
