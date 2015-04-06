<?php

class PCUserService {

    public static function GetPCUsers() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array();
            $csql = "select count(u.id) from " . PCUser::GetDSN() . " as u inner join " . PCUserAccountNumber::GetDSN() . " as a on u.id = a.pc_user";
            $sql = "select u.id,concat('PC',a.account_number) as username,u.name,u.address,u.city,u.phone,u.email,u.creation_date,u.last_changed from " . PCUser::GetDSN() . " as u inner join " . PCUserAccountNumber::GetDSN() . " as a on u.id = a.pc_user order by a.account_number";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PCUser');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/plantation-city/users?');
            $pagination->setPages();
            $users = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['users'] = $users;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetPCUserNames() {
        try {
            $values = array();
            $sql = "select u.id,concat('PC',a.account_number) as user_id,u.name from " . PCUser::GetDSN() . " as u inner join " . PCUserAccountNumber::GetDSN() . " as a on u.id = a.pc_user order by a.account_number";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PCUser');
            $users = $query->executeQuery($sql, $values, 0, 100000);
            $_GET['users'] = $users;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetUserData() {
        try {
            $values = array(UserService::GetUser()->getValue('username'));
            $sql = "select p.name,p.address,p.city,p.phone,p.email from " . PCUser::GetDSN() . " as p inner join " . PCUserAccountNumber::GetDSN() . " as a inner join " . User::GetDSN() . " as u on p.id = a.prepaid_user and a.account_number = u.username where u.username = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PCUser');
            $users = $query->executeQuery($sql, $values, 0, 1);
            $_GET['user'] = $users[0];
            $_GET['name'] = $_GET['user']->getValue('name');
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

            $users = new PersistableListObject(null, null);

            foreach ($data as $id => $row) {

                $assertProperties->addProperty($row, 'name');
                $assertProperties->addProperty($row, 'phone');
                $assertProperties->addProperty($row, 'email');
                $assertProperties->addProperty($row, 'address');
                $assertProperties->addProperty($row, 'city');
                $assertProperties->assert();

                $user = $pm->getObjectById('PCUser', $id);

                $pm->beginTransaction();

                $sms = false;
                $order = null;

                $user->setValue('name', $row['name']);
                $user->setValue('phone', $row['phone']);
                $user->setValue('email', $row['email']);
                $user->setValue('address', $row['address']);
                $user->setValue('city', $row['city']);

                $pm->save($user);

                $pm->commit();

                $sql = "select u.id,concat('PC',a.account_number) as username,u.name,u.address,u.city,u.phone,u.email,u.creation_date,u.last_changed from " . PCUser::GetDSN() . " as u inner join " . PCUserAccountNumber::GetDSN() . " as a on u.id = a.pc_user where u.id = ?";
                $query = $pm->getQueryBuilder('PCUser');

                $_users = $query->executeQuery($sql, array($user->getValue('id')), 0, 1);
                $users->add($_users[0]);

                
            }

            $_GET['users'] = $users;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoUpdatePassword() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'password');
            $assertProperties->addProperty($_POST, 'id');
            $assertProperties->assert();

            $sql = "select a.account_number,u.phone from " . PCUser::GetDSN() . " as u inner join " . PCUserAccountNumber::GetDSN() . " as a on u.id = a.pc_user where u.id = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PCUser');
            $users = $query->executeQuery($sql, array($_POST['id']), 0, 1);
            $user = $pm->getObjectByColumn('User', 'username',$users[0]->getValue('account_number'));
            $user->setValue('password', md5($_POST['password'] . $user->getValue('id') . 'genesisville'));
            $pm->save($user);
            SMSService::SendPCUserPasswordChange($_POST['password'], $users[0]->getValue('account_number'), $users[0]->getValue('phone'));
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function UpdateUserData() {
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'name');
            $assertProperties->addProperty($_POST, 'phone');
            $assertProperties->addProperty($_POST, 'email');
            $assertProperties->addProperty($_POST, 'address');
            $assertProperties->addProperty($_POST, 'city');
            $assertProperties->assert();
            $values = array(UserService::GetUser()->getValue('username'));
            $sql = "select p.id from " . PCUser::GetDSN() . " as p inner join " . PCUserAccountNumber::GetDSN() . " as a inner join " . User::GetDSN() . " as u on p.id = a.prepaid_user and a.account_number = u.username where u.username = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PCUser');
            $users = $query->executeQuery($sql, $values, 0, 1);
            $user = $pm->getObjectById('PCUser', $users[0]->getValue('id'));
            $user->setValue('name', $_POST['name']);
            $user->setValue('phone', $_POST['phone']);
            $user->setValue('email', $_POST['email']);
            $user->setValue('address', $_POST['address']);
            $user->setValue('city', $_POST['city']);
            $pm->save($user);
            $_GET['msg'] = 'Personal Details Updated';
            $_GET['user'] = $user;
            $_GET['name'] = $_GET['user']->getValue('name');
        } catch (Exception $e) {
            echo $e;
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetLoginUserName() {
        try {
            $values = array(UserService::GetUser()->getValue('username'));
            $sql = "select p.name from " . PCUser::GetDSN() . " as p inner join " . PCUserAccountNumber::GetDSN() . " as a inner join " . User::GetDSN() . " as u on p.id = a.c_user and a.account_number = u.username where u.username = ?";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PCUser');
            $users = $query->executeQuery($sql, $values, 0, 1);
            return $users[0]->getValue('name');
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetNewAccountNumber(){
        $pm = PersistenceManager::NewPersistenceManager();
        $sql = "select account_number from ".PCUserAccountNumber::GetDSN()." order by account_number desc";
        $query = $pm->getQueryBuilder('PCUserAccountNumber');
        $prepaidUserAccountNumbers = $query->executeQuery($sql, array(), 0, 1);
        if($prepaidUserAccountNumbers->count() > 0){
            return ($prepaidUserAccountNumbers[0]->getValue('account_number') + 1);
        }
        else{
            return 100;
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
                $pm->deleteByObjectId('PCUser', $row);
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            echo $e;
        }
    }

    public static function GetCurrentInfo($id) {
        try {
            $values = array($id, 3);
            $sql = "select o.plan,o.expires from " . PCOrder::GetDSN() . " as o where o.user = ? and o.status = ? order by o.creation_date desc";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PCUser');
            $users = $query->executeQuery($sql, $values, 0, 1);
            return array('plan' => $users[0]->getValue('plan'), 'expires' => $users[0]->getValue('expires'));
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetCurrentUserInfo() {
        try {
            $values = array(UserService::GetUser()->getValue('username'), 3);
            $sql = "select o.plan,o.expires from " . PCOrder::GetDSN() . " as o inner join ".PCUserAccountNumber::GetDSN()." as a on o.user = a.prepaid_user where a.account_number = ? and o.status = ? order by o.creation_date desc";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PCUser');
            $users = $query->executeQuery($sql, $values, 0, 1);
            return array('plan' => $users[0]->getValue('plan'), 'expires' => $users[0]->getValue('expires'));
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function GetCurrentPendingInfo($id) {
        try {
            $values = array($id, 3);
            $sql = "select o.plan,o.expires from " . PCOrder::GetDSN() . " as o where o.user = ? and o.status < ? order by o.creation_date desc";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PCUser');
            $users = $query->executeQuery($sql, $values, 0, 1);
            return array('plan' => $users[0]->getValue('plan'), 'expires' => $users[0]->getValue('expires'));
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function SendReminders() {
        try {
            $values = array();
            $sql = "select u.id,a.account_number as username,u.phone from " . PCUser::GetDSN() . " as u inner join " . PCUserAccountNumber::GetDSN() . " as a on u.id = a.pc_user";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PCUser');
            $users = $query->executeQuery($sql, $values, 0, 100000);
            for ($i = 0; $i < $users->count(); $i++) {
                $info = self::GetCurrentInfo($users[$i]->getValue('id'));
                $expires = strtotime($info['expires']);
                $now = strtotime(date('Y-m-d'));
                if ($expires >= $now) {
                    $diff = abs($expires - $now);
                    $days = floor($diff / (60 * 60 * 24));
                    if ($days == 7 || $days == 2) {
                        SMSService::SendPrepaidReminder("PC".$users[$i]->getValue('username'), Date('d/m/Y', $expires), $days, $users[$i]->getValue('phone'));
                    }
                }
            }
        } catch (Exception $e) {
            MailService::SendError("Reminders - PC", $e->getMessage());
        }
    }

}

?>
