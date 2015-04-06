<?php

class StationUserService {

    public static function GetUsers() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array(0);
            $csql = "select count(*) from " . StationUser::GetDSN();
            $sql = "select * from " . StationUser::GetDSN() . " order by station, user";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('StationUser');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/station/users?');
            $pagination->setPages();
            $users = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['users'] = $users;
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
            $assertProperties->addProperty($_POST, 'user');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $pm->beginTransaction();
            $user = new StationUser();
            $user->setValue('station', $_POST['station']);
            $user->setValue('user', $_POST['user']);
            $pm->save($user);
            
            $sql = "select count(*) from " . StationUser::GetDSN() . " where user = ?";
            $query = $pm->getQueryBuilder('StationUser');
            $row = $query->execute($sql, array($_POST['user']))->fetch();
            if ($row[0] > 1) {
                throw new Exception($_POST['user'] . ' already exist in a Station');
            }
            
            $pm->commit();

            $users = new PersistableListObject(null, null);
            $users->add($user);
            
            $_GET['users'] = $users;
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
                $pm->deleteByObjectId('StationUser', $row);
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            echo $e;
        }
    }
    
}

?>
