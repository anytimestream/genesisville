<?php

class StationPlanService {

    public static function GetPlans() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array(0);
            $csql = "select count(*) from " . StationPlan::GetDSN();
            $sql = "select s.id,s.station,s.plan,s.contract_value,s.creation_date,s.last_changed,p.amount from " . StationPlan::GetDSN() . " as s inner join " . Plan::GetDSN() . " as p on s.plan = p.name order by station, plan";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('StationPlan');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/station/plans?');
            $pagination->setPages();
            $plans = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['plans'] = $plans;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoInsert() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'station');
            $assertProperties->addProperty($_POST, 'plan');
            $assertProperties->addProperty($_POST, 'contract_value');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $pm->beginTransaction();
            $plan = new StationPlan();
            $plan->setValue('station', $_POST['station']);
            $plan->setValue('plan', $_POST['plan']);
            $plan->setValue('contract_value', $_POST['contract_value']);
            $pm->save($plan);

            $sql = "select count(*) from " . StationPlan::GetDSN() . " where station = ? and plan = ?";
            $query = $pm->getQueryBuilder('StationPlan');
            $row = $query->execute($sql, array($_POST['station'], $_POST['plan']))->fetch();
            if ($row[0] > 1) {
                throw new Exception('Station ' . $_POST['station'] . ' already contains Plan ' . $_POST['plan']);
            }
            
            $pm->commit();

            $sql = "select s.id,s.station,s.plan,s.contract_value,s.creation_date,s.last_changed,p.amount from " . StationPlan::GetDSN() . " as s inner join " . Plan::GetDSN() . " as p on s.plan = p.name where s.id = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('StationPlan');
            $_plans = $query->executeQuery($sql, array($plan->getValue('id')), 0, 1);

            $plans = new PersistableListObject(null, null);
            $plans->add($_plans[0]);
            
            $_GET['plans'] = $plans;
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

            $plans = new PersistableListObject(null, null);

            foreach ($data as $id => $row) {

                $assertProperties->addProperty($row, 'contract_value');
                $assertProperties->assert();

                $plan = $pm->getObjectById('StationPlan', $id);

                $plan->setValue('contract_value', $row['contract_value']);

                $pm->save($plan);

                $sql = "select s.id,s.station,s.plan,s.contract_value,s.creation_date,s.last_changed,p.amount from " . StationPlan::GetDSN() . " as s inner join " . Plan::GetDSN() . " as p on s.plan = p.name where s.id = ?";
                $pm = PersistenceManager::NewPersistenceManager();
                $query = $pm->getQueryBuilder('StationPlan');
                $_plans = $query->executeQuery($sql, array($plan->getValue('id')), 0, 1);

                $plans->add($_plans[0]);
            }

            $_GET['plans'] = $plans;
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
                $pm->deleteByObjectId('StationPlan', $row);
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            echo $e;
        }
    }

}

?>
