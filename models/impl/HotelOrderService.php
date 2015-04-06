<?php

class HotelOrderService {

    public static function GetOrders() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array(date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');
            $url_search = 'from=' . date('d/m/Y');
            $url_search .= '&to=' . date('d/m/Y');
            $summary_url = date('d/m/Y');

            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = explode("/", $_GET['from']);
                $to = explode("/", $_GET['to']);
                if (count($from) == 3 && count($to) == 3) {
                    $values[0] = $from[2] . '-' . Util::AddLeadingZeros($from[1], 2) . '-' . Util::AddLeadingZeros($from[0], 2) . ' 00:00:00';
                    $values[1] = $to[2] . '-' . Util::AddLeadingZeros($to[1], 2) . '-' . Util::AddLeadingZeros($to[0], 2) . ' 23:59:59';
                    $summary_url = $_GET['from'] . ' - ' . $_GET['to'];
                    $url_search = 'from=' . $_GET['from'];
                    $url_search .= '&to=' . $_GET['to'];
                }
            }

            $sql = "select o.id,o.plan,o.phone,o.hotel,o.user,p.amount,p.transaction_id,o.point,o.creation_date,o.last_changed from " . HotelOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.creation_date between ? and ?";
            $csql = "select count(*) from " . HotelOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.creation_date between ? and ?";

            if (isset($_GET['hotel']) && $_GET['hotel'] != 'all') {
                $sql .= " and o.hotel = ?";
                $csql .= " and o.hotel = ?";
                $values[] = urldecode($_GET['hotel']);
                $summary_url .= ' Hotel: ' . urldecode($_GET['hotel']);
                $url_search .= '&hotel=' . $_GET['hotel'];
            }

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('HotelOrder');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/hotel/orders?' . $url_search . '&');
            $pagination->setPages();
            $orders = $query->executeQuery($sql . ' order by o.creation_date desc', $values, (($index - 1) * $size), $size);
            $_GET['orders'] = $orders;
            $_GET['pagination'] = $pagination;
            $_GET['url_search'] = $url_search;
            $_GET['summary_url'] = $summary_url;
            HotelService::GetHotelNames();
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetSummary() {
        try {
            $values = array(1, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');

            $sql = "select sum(o.point) as point,sum(p.amount) as amount, count(o.id) as total from " . HotelOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where p.status = ? and o.creation_date between ? and ?";

            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = explode("/", $_GET['from']);
                $to = explode("/", $_GET['to']);
                if (count($from) == 3 && count($to) == 3) {
                    $values[1] = $from[2] . '-' . Util::AddLeadingZeros($from[1], 2) . '-' . Util::AddLeadingZeros($from[0], 2) . ' 00:00:00';
                    $values[2] = $to[2] . '-' . Util::AddLeadingZeros($to[1], 2) . '-' . Util::AddLeadingZeros($to[0], 2) . ' 23:59:59';
                }
            }

            if (isset($_GET['hotel']) && $_GET['hotel'] != 'all') {
                $sql .= " and o.hotel = ?";
                $values[] = urldecode($_GET['hotel']);
            }

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('HotelOrder');
            $orders = $query->executeQuery($sql, $values, 0, 1);
            $_GET['summary'] = $orders[0];
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetHotelOrders() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $pm = PersistenceManager::NewPersistenceManager();
            $hotel = $pm->getObjectByColumn('HotelUser', 'user', UserService::GetUser()->getValue('username'))->getValue('hotel');
            $values = array($hotel, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');
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

            $sql = "select o.id,o.plan,o.phone,o.user,p.amount,p.transaction_id,o.point,o.creation_date,o.last_changed from " . HotelOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.hotel = ? and o.creation_date between ? and ?";
            $csql = "select count(*) from " . HotelOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.hotel = ? and o.creation_date between ? and ?";

            if (isset($_GET['user']) && $_GET['user'] != 'all') {
                $sql .= " and o.user = ?";
                $csql .= " and o.user = ?";
                $values[] = urldecode($_GET['user']);
                $summary_url .= ' User: ' . urldecode($_GET['user']);
                $url_search .= '&user=' . $_GET['user'];
            }

            $query = $pm->getQueryBuilder('HotelOrder');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/hotel/orders?' . $url_search . '&');
            $pagination->setPages();
            $orders = $query->executeQuery($sql . ' order by o.creation_date desc', $values, (($index - 1) * $size), $size);
            $_GET['orders'] = $orders;
            $_GET['pagination'] = $pagination;
            $_GET['url_search'] = $url_search;
            $_GET['summary_url'] = $summary_url;
            UserService::GetUserNamesByType('Hotel');
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function GetHotelSummary() {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $hotel = $pm->getObjectByColumn('HotelUser', 'user', UserService::GetUser()->getValue('username'))->getValue('hotel');
            $values = array($hotel, 1, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');

            $sql = "select sum(o.point) as point,sum(p.amount) as amount, count(o.id) as total, o.user from " . HotelOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.hotel = ? and p.status = ? and o.creation_date between ? and ?";

            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = explode("/", $_GET['from']);
                $to = explode("/", $_GET['to']);
                if (count($from) == 3 && count($to) == 3) {
                    $values[2] = $from[2] . '-' . Util::AddLeadingZeros($from[1], 2) . '-' . Util::AddLeadingZeros($from[0], 2) . ' 00:00:00';
                    $values[3] = $to[2] . '-' . Util::AddLeadingZeros($to[1], 2) . '-' . Util::AddLeadingZeros($to[0], 2) . ' 23:59:59';
                }
            }

            if (isset($_GET['user']) && $_GET['user'] != 'all') {
                $sql .= " and o.user = ?";
                $values[] = urldecode($_GET['user']);
            }

            $amount = 0;
            $tickets = 0;
            $point = 0;

            $query = $pm->getQueryBuilder('HotelOrder');
            $orders = $query->executeQuery($sql." group by o.user", $values, 0, 1000);

            for ($i = 0; $i < $orders->count(); $i++) {
                $amount += $orders[$i]->getValue('amount');
                $point += $orders[$i]->getValue('point');
                $tickets += $orders[$i]->getValue('total');
            }
            
            $_GET['summary'] = array('amount' => $amount, 'tickets' => $tickets, 'point' => $point);
            $_GET['orders'] = $orders;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function PrintTicketByOrderId($id) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('HotelOrder');
            $sql = "select c.username,c.password,o.plan,o.phone,h.name as hotel,h.address,h.phone as hotel_phone,p.validity,p.uptime,pa.transaction_id,pa.amount,o.creation_date from " . HotelOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as pa Inner Join " . Card::GetDSN() . " as c inner join ".Hotel::GetDSN()." as h inner join ".Plan::GetDSN()." as p on o.id = pa.related_to and pa.transaction_id = c.transaction_id and o.hotel = h.name and o.plan = p.name where o.id = ?";
            $orders = $query->executeQuery($sql, array($id), 0, 1);
            if ($orders->count() == 0) {
                throw new Exception("Invalid Order");
            }
            $_GET['order'] = $orders[0];
        } catch (Exception $e) {
            echo $e;
            $_GET['error'] = $e->getMessage();
        }
    }
}

?>
