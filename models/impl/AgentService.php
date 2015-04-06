<?php

class AgentService {

    public static function GetAgents() {
        try {
            $size = 50;
            $index = 1;
            if(isset($_GET['page'])){
                $index = $_GET['page'];
            }
            $values = array(0);
            $csql = "select count(*) from " . Agent::GetDSN()." where deleted = ?";;
            $sql = "select * from " . Agent::GetDSN() . " where deleted = ? order by name";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Agent');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/prepaid/agents?');
            $pagination->setPages();
            $agents = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['agents'] = $agents;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function GetAgentNames() {
        try {
            $values = array(0);
            $sql = "select name from " . Agent::GetDSN() . " where deleted = ? order by name";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Agent');
            $agents = $query->executeQuery($sql, $values, 0, 100000);
            $_GET['agents'] = $agents;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetNoOfUsers($agent) {
        try {
            $values = array($agent);
            $csql = "select count(*) from " . PrepaidUser::GetDSN() . " where agent = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PrepaidUser');
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
            $assertProperties->addProperty($_POST, 'firstname');
            $assertProperties->addProperty($_POST, 'lastname');
            $assertProperties->addProperty($_POST, 'phone');
            $assertProperties->addProperty($_POST, 'email');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $agent = new Agent();
            $agent->setValue('name', $_POST['firstname'].' '.$_POST['lastname']);
            $agent->setValue('phone', $_POST['phone']);
            $agent->setValue('email', $_POST['email']);
            $pm->save($agent);

            $agents = new PersistableListObject(null, null);
            $agents->add($agent);
            $_GET['agents'] = $agents;
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

            $agents = new PersistableListObject(null, null);

            foreach ($data as $id => $row) {

                $assertProperties->addProperty($row, 'name');
                $assertProperties->addProperty($row, 'phone');
                $assertProperties->addProperty($row, 'email');
                $assertProperties->assert();

                $agent = $pm->getObjectById('Agent', $id);

                $agent->setValue('name', $row['name']);
                $agent->setValue('phone', $row['phone']);
                $agent->setValue('email', $row['email']);

                $pm->save($agent);

                $agents->add($agent);
            }

            $_GET['agents'] = $agents;
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
                $agent = $pm->getObjectById('Agent', $row);
                $agent->setValue('deleted', 1);
                $pm->save($agent);
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

}

?>
