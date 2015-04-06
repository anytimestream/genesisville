<?php

class OrderService {

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

            $sql = "select o.id,o.plan,pgt.response_code,pgt.response_description,pgt.status,pgt.merchant_reference,o.phone,o.email,p.amount,p.transaction_id,o.creation_date,o.last_changed from " . CardOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p inner join ".PaymentGatewayTransaction::GetDSN()." as pgt on o.id = p.related_to and o.transaction_id = pgt.id where pgt.status = ? and o.creation_date between ? and ?";
            $csql = "select count(*) from " . CardOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p inner join ".PaymentGatewayTransaction::GetDSN()." as pgt on o.id = p.related_to and o.transaction_id = pgt.id where pgt.status = ? and o.creation_date between ? and ?";

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('CardOrder');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/hotspot/orders?' . $url_search . '&');
            $pagination->setPages();
            $orders = $query->executeQuery($sql . ' order by o.creation_date desc', $values, (($index - 1) * $size), $size);
            $_GET['orders'] = $orders;
            $_GET['pagination'] = $pagination;
            $_GET['url_search'] = $url_search;
            $_GET['summary_url'] = $summary_url;
        } catch (Exception $e) {
            echo $e;
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

            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = explode("/", $_GET['from']);
                $to = explode("/", $_GET['to']);
                if (count($from) == 3 && count($to) == 3) {
                    $values[1] = $from[2] . '-' . Util::AddLeadingZeros($from[1], 2) . '-' . Util::AddLeadingZeros($from[0], 2) . ' 00:00:00';
                    $values[2] = $to[2] . '-' . Util::AddLeadingZeros($to[1], 2) . '-' . Util::AddLeadingZeros($to[0], 2) . ' 23:59:59';
                    $url_search = 'from=' . $_GET['from'];
                    $url_search .= '&to=' . $_GET['to'];
                }
            }

            $sql = "select o.id,o.plan,o.phone,o.email,pgt.response_code,pgt.response_description,pgt.status,pgt.merchant_reference,o.creation_date,o.last_changed from " . CardOrder::GetDSN() . " as o inner join ".PaymentGatewayTransaction::GetDSN()." as pgt on o.transaction_id = pgt.id where pgt.status < ? and o.creation_date between ? and ?";
            $csql = "select count(*) from " . CardOrder::GetDSN() . " as o inner join ".PaymentGatewayTransaction::GetDSN()." as pgt on o.transaction_id = pgt.id where pgt.status < ? and o.creation_date between ? and ?";

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('CardOrder');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/hotspot/pending-orders?' . $url_search . '&');
            $pagination->setPages();
            $orders = $query->executeQuery($sql . ' order by o.creation_date desc', $values, (($index - 1) * $size), $size);
            $_GET['orders'] = $orders;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            echo $e;
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function GetSummary() {
        try {
            $values = array(1, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');

            $sql = "select sum(p.amount) as amount, count(o.id) as total from " . CardOrder::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where p.status = ? and o.creation_date between ? and ?";

            if (isset($_GET['from']) && isset($_GET['to'])) {
                $from = explode("/", $_GET['from']);
                $to = explode("/", $_GET['to']);
                if (count($from) == 3 && count($to) == 3) {
                    $values[1] = $from[2] . '-' . Util::AddLeadingZeros($from[1], 2) . '-' . Util::AddLeadingZeros($from[0], 2) . ' 00:00:00';
                    $values[2] = $to[2] . '-' . Util::AddLeadingZeros($to[1], 2) . '-' . Util::AddLeadingZeros($to[0], 2) . ' 23:59:59';
                }
            }

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('CardOrder');
            $cardOrders = $query->executeQuery($sql, $values, 0, 1);
            $_GET['summary'] = $cardOrders[0];
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

}

?>
