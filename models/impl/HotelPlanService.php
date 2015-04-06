<?php

class HotelPlanService {

    public static function GetPlans() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array(0);
            $csql = "select count(*) from " . HotelPlan::GetDSN();
            $sql = "select * from " . HotelPlan::GetDSN() . " order by hotel, plan";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('HotelPlan');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/hotel/plans?');
            $pagination->setPages();
            $plans = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['plans'] = $plans;
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

            $plans = new PersistableListObject(null, null);

            foreach ($data as $id => $row) {

                $assertProperties->addProperty($row, 'point');
                $assertProperties->assert();

                $plan = $pm->getObjectById('HotelPlan', $id);

                $plan->setValue('point', $row['point']);

                $pm->save($plan);
                $plans->add($plan);
            }

            $_GET['plans'] = $plans;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoInsert() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'hotel');
            $assertProperties->addProperty($_POST, 'plan');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $pm->beginTransaction();
            $plan = new HotelPlan();
            $plan->setValue('hotel', $_POST['hotel']);
            $plan->setValue('plan', $_POST['plan']);
            $plan->setValue('point', $_POST['point']);
            $pm->save($plan);

            $sql = "select count(*) from " . HotelPlan::GetDSN() . " where hotel = ? and plan = ?";
            $query = $pm->getQueryBuilder('HotelPlan');
            $row = $query->execute($sql, array($_POST['hotel'], $_POST['plan']))->fetch();
            if ($row[0] > 1) {
                throw new Exception('Hotel ' . $_POST['hotel'] . ' already contains Plan ' . $_POST['plan']);
            }
            
            $pm->commit();

            $plans = new PersistableListObject(null, null);
            $plans->add($plan);
            
            $_GET['plans'] = $plans;
        } catch (Exception $e) {
            $pm->rollBack();
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
                $pm->deleteByObjectId('HotelPlan', $row);
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            echo $e;
        }
    }

}

?>
