<?php

class StationOrderService {

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

            $sql = "select o.id,o.plan,o.phone,o.station,o.user,p.amount,p.transaction_id,o.contract_value,o.creation_date,o.last_changed from " . StationOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.creation_date between ? and ?";
            $csql = "select count(*) from " . StationOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.creation_date between ? and ?";

            if (isset($_GET['station']) && $_GET['station'] != 'all') {
                $sql .= " and o.station = ?";
                $csql .= " and o.station = ?";
                $values[] = urldecode($_GET['station']);
                $summary_url .= ' Station: ' . urldecode($_GET['station']);
                $url_search .= '&station=' . $_GET['station'];
            }

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('StationOrder');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/station/orders?' . $url_search . '&');
            $pagination->setPages();
            $orders = $query->executeQuery($sql . ' order by o.creation_date desc', $values, (($index - 1) * $size), $size);
            $_GET['orders'] = $orders;
            $_GET['pagination'] = $pagination;
            $_GET['url_search'] = $url_search;
            $_GET['summary_url'] = $summary_url;
            StationService::GetStationNames();
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
            $values = array($station, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');
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

            $sql = "select o.id,o.plan,o.phone,o.user,p.amount,p.transaction_id,o.contract_value,o.creation_date,o.last_changed from " . StationOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.station = ? and o.creation_date between ? and ?";
            $csql = "select count(*) from " . StationOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.station = ? and o.creation_date between ? and ?";

            if (isset($_GET['user']) && $_GET['user'] != 'all') {
                $sql .= " and o.user = ?";
                $csql .= " and o.user = ?";
                $values[] = urldecode($_GET['user']);
                $summary_url .= ' User: ' . urldecode($_GET['user']);
                $url_search .= '&user=' . $_GET['user'];
            }

            $query = $pm->getQueryBuilder('StationOrder');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/station/orders?' . $url_search . '&');
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

    public static function GetSummary() {
        try {
            $values = array(1, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');

            $sql = "select sum(o.contract_value) as contract_value,sum(p.amount) as amount, count(o.id) as total from " . StationOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where p.status = ? and o.creation_date between ? and ?";

            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = explode("/", $_GET['from']);
                $to = explode("/", $_GET['to']);
                if (count($from) == 3 && count($to) == 3) {
                    $values[1] = $from[2] . '-' . Util::AddLeadingZeros($from[1], 2) . '-' . Util::AddLeadingZeros($from[0], 2) . ' 00:00:00';
                    $values[2] = $to[2] . '-' . Util::AddLeadingZeros($to[1], 2) . '-' . Util::AddLeadingZeros($to[0], 2) . ' 23:59:59';
                }
            }

            if (isset($_GET['station']) && $_GET['station'] != 'all') {
                $sql .= " and o.station = ?";
                $values[] = urldecode($_GET['station']);
            }

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('StationOrder');
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
            $values = array($station, 1, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');

            $sql = "select sum(o.contract_value) as contract_value,sum(p.amount) as amount, count(o.id) as total, o.user from " . StationOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where o.station = ? and p.status = ? and o.creation_date between ? and ?";

            $url_search = 'from=' . date('d/m/Y');
            $url_search .= '&to=' . date('d/m/Y');

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
            $contract_value = 0;

            $query = $pm->getQueryBuilder('StationOrder');
            $orders = $query->executeQuery($sql . " group by o.user", $values, 0, 1000);

            for ($i = 0; $i < $orders->count(); $i++) {
                $amount += $orders[$i]->getValue('amount');
                $contract_value += $orders[$i]->getValue('contract_value');
                $tickets += $orders[$i]->getValue('total');
            }

            $_GET['summary'] = array('amount' => $amount, 'tickets' => $tickets, 'contract_value' => $contract_value);
            $_GET['orders'] = $orders;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function PrintTicketByOrderId($id) {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('StationOrder');
            $sql = "select c.username,c.password,o.plan,o.phone,s.name as station,s.address,s.phone as station_phone,p.validity,p.uptime,pa.transaction_id,pa.amount,o.creation_date from " . StationOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as pa Inner Join " . Card::GetDSN() . " as c inner join " . Station::GetDSN() . " as s inner join " . Plan::GetDSN() . " as p on o.id = pa.related_to and pa.transaction_id = c.transaction_id and o.station = s.name and o.plan = p.name where o.id = ?";
            $orders = $query->executeQuery($sql, array($id), 0, 1);
            if ($orders->count() == 0) {
                throw new Exception("Invalid Order");
            }
            $_GET['order'] = $orders[0];
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

}

?>
