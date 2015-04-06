<?php

class PrepaidPlanService {

    public static function GetPrepaidPlans() {
        try {
            $size = 50;
            $index = 1;
            if(isset($_GET['page'])){
                $index = $_GET['page'];
            }
            $values = array(0);
            $csql = "select count(*) from " . PrepaidPlan::GetDSN()." where deleted = ?";;
            $sql = "select * from " . PrepaidPlan::GetDSN() . " where deleted = ? order by name";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidPlan');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/prepaid/plans?');
            $pagination->setPages();
            $plans = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['plans'] = $plans;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function GetPrepaidPlanNames() {
        try {
            $values = array(0);
            $sql = "select name, amount from " . PrepaidPlan::GetDSN() . " where deleted = ? order by name";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidPlan');
            $plans = $query->executeQuery($sql, $values, 0, 100000);
            $_GET['plans'] = $plans;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoInsert() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'name');
            $assertProperties->addProperty($_POST, 'amount');
            $assertProperties->addProperty($_POST, 'description');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $plan = new PrepaidPlan();
            $plan->setValue('name', $_POST['name']);
            $plan->setValue('amount', $_POST['amount']);
            $plan->setValue('description', $_POST['description']);
            $pm->save($plan);

            $plans = new PersistableListObject(null, null);
            $plans->add($plan);
            $_GET['plans'] = $plans;
        } catch (Exception $e) {
            echo $e;
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

                $assertProperties->addProperty($row, 'name');
                $assertProperties->addProperty($row, 'amount');
                $assertProperties->addProperty($row, 'description');
                $assertProperties->assert();

                $plan = $pm->getObjectById('PrepaidPlan', $id);

                $plan->setValue('name', $row['name']);
                $plan->setValue('amount', $row['amount']);
                $plan->setValue('description', $row['description']);

                $pm->save($plan);

                $plans->add($plan);
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
                $plan = $pm->getObjectById('PrepaidPlan', $row);
                $plan->setValue('deleted', 1);
                $pm->save($plan);
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

}

?>
