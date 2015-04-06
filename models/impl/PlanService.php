<?php

class PlanService {

    public static function GetPlans() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array(0);
            $csql = "select count(*) from " . Plan::GetDSN() . " where deleted = ?";
            $sql = "select * from " . Plan::GetDSN() . " where deleted = ? order by _group,name";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Plan');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/hotspot/plans?');
            $pagination->setPages();
            $plans = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['plans'] = $plans;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetPlanNames() {
        try {
            $values = array(0);
            $sql = "select name, amount from " . Plan::GetDSN() . " where deleted = ? order by _group,name";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Plan');
            $plans = $query->executeQuery($sql, $values, 0, 100000);
            $_GET['plans'] = $plans;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function SendReminder() {
        try {
            $sql = "select * from " . Plan::GetDSN() . " where deleted = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Plan');
            $plans = $query->executeQuery($sql, array(0), 0, 10000);
            for ($i = 0; $i < $plans->count(); $i++) {
                $available = self::GetAvailable($plans[$i]->getValue('name'));
                if ($plans[$i]->getValue('notify') > 0 && $plans[$i]->getValue('notify') >= $available) {
                    SMSService::SendTicketReminder($plans[$i]->getValue('name'), $available);
                }
            }
        } catch (Exception $e) {
            MailService::SendError("Reminders - Tickets", $e->getMessage());
        }
    }

    public static function GetAvailable($plan) {
        try {
            $values = array(0, $plan);
            $csql = "select count(*) from " . Card::GetDSN() . " where status = ? and plan = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Plan');
            $row = $query->execute($csql, $values)->fetch();
            return $row[0];
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetSold($plan) {
        try {
            $values = array(1, $plan);
            $csql = "select count(*) from " . Card::GetDSN() . " where status = ? and plan = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Plan');
            $row = $query->execute($csql, $values)->fetch();
            return $row[0];
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
            $assertProperties->addProperty($_POST, 'group');
            $assertProperties->addProperty($_POST, 'notify');
            $assertProperties->addProperty($_POST, 'validity');
            $assertProperties->addProperty($_POST, 'uptime');
            $assertProperties->addProperty($_POST, 'description');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $plan = new Plan();
            $plan->setValue('name', $_POST['name']);
            $plan->setValue('amount', $_POST['amount']);
            $plan->setValue('_group', $_POST['group']);
            $plan->setValue('notify', $_POST['notify']);
            $plan->setValue('validity', $_POST['validity']);
            $plan->setValue('uptime', $_POST['uptime']);
            $plan->setValue('description', $_POST['description']);
            $pm->save($plan);

            $plans = new PersistableListObject(null, null);
            $plans->add($plan);
            $_GET['plans'] = $plans;
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

                $assertProperties->addProperty($row, 'name');
                $assertProperties->addProperty($row, 'amount');
                $assertProperties->addProperty($row, 'group');
                $assertProperties->addProperty($row, 'notify');
                $assertProperties->addProperty($row, 'validity');
                $assertProperties->addProperty($row, 'uptime');
                $assertProperties->addProperty($row, 'description');
                $assertProperties->assert();

                $plan = $pm->getObjectById('Plan', $id);

                $plan->setValue('name', $row['name']);
                $plan->setValue('amount', $row['amount']);
                $plan->setValue('_group', $row['group']);
                $plan->setValue('notify', $row['notify']);
                $plan->setValue('validity', $row['validity']);
                $plan->setValue('uptime', $row['uptime']);
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
                $plan = $pm->getObjectById('Plan', $row);
                $plan->setValue('deleted', 1);
                $pm->save($plan);
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

}

?>
