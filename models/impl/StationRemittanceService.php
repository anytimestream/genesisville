<?php

class StationRemittanceService {

    public static function GetRemittance() {
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

            $sql = "select r.id,r.station,p.method,r.remarks,p.amount,p.transaction_id,p.status,r.creation_date,r.last_changed from " . Payment::GetDSN() . " as p inner Join " . StationRemittance::GetDSN() . " as r on r.id = p.related_to where r.creation_date between ? and ?";
            $csql = "select count(*) from " . Payment::GetDSN() . " as p Inner Join " . StationRemittance::GetDSN() . " as r on r.id = p.related_to where r.creation_date between ? and ?";

            if (isset($_GET['station']) && $_GET['station'] != 'all') {
                $sql .= " and r.station = ?";
                $csql .= " and r.station = ?";
                $values[] = urldecode($_GET['station']);
                $summary_url .= ' Station: ' . urldecode($_GET['station']);
                $url_search .= '&station=' . $_GET['station'];
            }

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('StationRemittance');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/station/remittance?' . $url_search . '&');
            $pagination->setPages();
            $remittance = $query->executeQuery($sql . ' order by r.creation_date desc', $values, (($index - 1) * $size), $size);
            $_GET['remittance'] = $remittance;
            $_GET['pagination'] = $pagination;
            $_GET['url_search'] = $url_search;
            $_GET['summary_url'] = $summary_url;
            StationService::GetStationNames();
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoInsert() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'station');
            $assertProperties->addProperty($_POST, 'amount');
            $assertProperties->addProperty($_POST, 'method');
            $assertProperties->addProperty($_POST, 'remarks');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $pm->beginTransaction();
            $remittance = new StationRemittance();
            $remittance->setValue('station', $_POST['station']);
            $remittance->setValue('remarks', $_POST['remarks']);
            $pm->save($remittance);

            $payment = New Payment();
            $payment->setValue('amount', $_POST['amount']);
            $payment->setValue('method', $_POST['method']);
            $payment->setValue('type', "Station Remittance");
            $payment->setValue('related_to', $remittance->getValue('id'));
            $payment->setValue('status', 1);
            $pm->save($payment);

            $pm->commit();

            $sql = "select r.id,r.station,p.method,r.remarks,p.amount,p.transaction_id,p.status,r.creation_date,r.last_changed from " . Payment::GetDSN() . " as p inner Join " . StationRemittance::GetDSN() . " as r on r.id = p.related_to where r.id = ?";
            $query = $pm->getQueryBuilder('StationRemittance');
            $_remittance = $query->executeQuery($sql, array($remittance->getValue('id')), 0, 1);
            $_GET['remittance'] = $_remittance;
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

            $_remittance = new PersistableListObject(null, null);

            foreach ($data as $id => $row) {

                $assertProperties->addProperty($row, 'status');
                $assertProperties->assert();

                $payment = $pm->getObjectByColumn('Payment', 'related_to', $id);

                $payment->setValue('status', $row['status']);

                $pm->save($payment);

                $sql = "select r.id,r.station,p.method,r.remarks,p.amount,p.transaction_id,p.status,r.creation_date,r.last_changed from " . Payment::GetDSN() . " as p inner Join " . StationRemittance::GetDSN() . " as r on r.id = p.related_to where r.id = ?";
                $query = $pm->getQueryBuilder('StationRemittance');
                $remittance = $query->executeQuery($sql, array($id), 0, 1);

                $_remittance->add($remittance);
            }

            $_GET['remittance'] = $_remittance;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetSummary() {
        try {
            $values = array(1, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');

            $sql = "select sum(p.amount) as amount from " . StationRemittance::GetDSN() . " as o Inner Join " . Payment::GetDSN() . " as p on o.id = p.related_to where p.status = ? and o.creation_date between ? and ?";

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
            $query = $pm->getQueryBuilder('StationRemittance');
            $orders = $query->executeQuery($sql, $values, 0, 1);
            $_GET['summary'] = $orders[0];
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

}

?>
