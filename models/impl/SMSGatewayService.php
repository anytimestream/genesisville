<?php

class SMSGatewayService {

    public static function GetGateways() {
        try {
            $size = 50;
            $index = 1;
            if(isset($_GET['page'])){
                $index = $_GET['page'];
            }
            $values = array(0);
            $csql = "select count(*) from " . SMSGateway::GetDSN();
            $sql = "select * from " . SMSGateway::GetDSN();
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('SMSGateway');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/management/sms-gateways?');
            $pagination->setPages();
            $gateways = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['gateways'] = $gateways;
            $_GET['pagination'] = $pagination;
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

            $gateways = new PersistableListObject(null, null);

            foreach ($data as $id => $row) {

                $assertProperties->addProperty($row, 'is_default');
                $assertProperties->assert();

                $gateway = $pm->getObjectById('SMSGateway', $id);

                $gateway->setValue('isDefault', $row['is_default']);

                $pm->save($gateway);

                $gateways->add($gateway);
            }

            $_GET['gateways'] = $gateways;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

}

?>
