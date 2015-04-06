<?php

class UserService {

    public static function GetUsers() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array(0, 'station');
            $url_search = '';
            $csql = "select count(*) from " . User::GetDSN() . " where deleted = ? and type = ?";
            $sql = "";
            
            if (isset($_GET['type'])) {
                $values[1] = urldecode($_GET['type']);
                $url_search = 'type=' . $_GET['type'];
            }
            
            if($values[1] == 'station'){
                $sql = "select u.id,u.username,s.station as name,u.creation_date,u.last_changed from " . User::GetDSN() . " as u inner Join ".StationUser::GetDSN()." as s on u.username = s.user where u.deleted = ? and u.type = ? order by u.username";
            }
            else{
                $sql = "select u.id,u.username,h.hotel as name,u.creation_date,u.last_changed from " . User::GetDSN() . " as u inner Join ".HotelUser::GetDSN()." as h on u.username = h.user where u.deleted = ? and u.type = ? order by u.username";
            }
            
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('User');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/management/users?' . $url_search . '&');
            $pagination->setPages();
            $users = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['users'] = $users;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetUserNames() {
        try {
            $values = array();
            $sql = "select username from " . User::GetDSN() . " order by username";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('User');
            $users = $query->executeQuery($sql, $values, 0, 100000);
            $_GET['users'] = $users;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetStationUserNames() {
        try {
            $pm = PersistenceManager::NewPersistenceManager();
            $values = array($pm->getObjectByColumn('StationUser', 'user', UserService::GetUser()->getValue('username'))->getValue('station'));
            $sql = "select user from " . StationUser::GetDSN() . " where station = ? order by user";
            $query = $pm->getQueryBuilder('StationUser');
            $users = $query->executeQuery($sql, $values, 0, 100000);
            $_GET['users'] = $users;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetUserNamesByType($type) {
        try {
            $values = array($type);
            $sql = "select username from " . User::GetDSN() . " where type = ? order by username";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('User');
            $users = $query->executeQuery($sql, $values, 0, 100000);
            $_GET['users'] = $users;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoInsert() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'username');
            $assertProperties->addProperty($_POST, 'password');
            $assertProperties->addProperty($_POST, 'type');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $user = new User();
            $user->setValue('username', $_POST['username']);
            $user->setValue('password', md5($_POST['password'] . $user->getValue('id') . 'genesisville'));
            $user->setValue('type', $_POST['type']);
            $pm->save($user);

            $users = new PersistableListObject(null, null);
            $users->add($user);

            $_GET['users'] = $users;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoUpdate() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'password');
            $assertProperties->addProperty($_POST, 'id');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();
            $user = $pm->getObjectById('User', $_POST['id']);
            $user->setValue('password', md5($_POST['password'] . $user->getValue('id') . 'genesisville'));
            $pm->save($user);

            if ($user->getValue('type') == 'Prepaid') {
                $values = array($user->getValue('id'));
                $sql = "select p.phone from " . PrepaidUser::GetDSN() . " as p inner join ".User::GetDSN()." as u inner join ".PrepaidUserAccountNumber::GetDSN()." as a on p.id = a.prepaid_user and u.username = a.account_number where u.id = ?";
                $query = $pm->getQueryBuilder('PrepaidUser');
                $users = $query->executeQuery($sql, $values, 0, 1);
                
                SMSService::SendPrepaidUserPasswordChange($_POST['password'], $users[0]->getValue('phone'));
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoChangePassword() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'password');
            $assertProperties->addProperty($_POST, 'password2');
            $assertProperties->addProperty($_POST, 'password3');
            $assertProperties->assert();

            if ($_POST['password2'] != $_POST['password3']) {
                $_GET['msg'] = "Password and Confirm Password do not match";
                return;
            }

            $pm = PersistenceManager::getConnection();
            $user = $pm->getObjectById('User', UserService::GetUser()->getValue('id'));
            if ($user->getValue('password') != md5($_POST['password'] . $user->getValue('id') . 'genesisville')) {
                $_GET['msg'] = "Wrong Current Password";
                return;
            }
            if ($user->getValue('type') == 'Prepaid') {
                $_GET['msg'] = "Password change request sent. You will receive an sms when your new password is activated";
                $accountNumber = $pm->getObjectByColumn('PrepaidUserAccountNumber', 'account_number', $user->getValue('username'));
                PasswordChangeRequestService::DoInsert($accountNumber->getValue('prepaid_user'), $_POST['password']);
                return;
            }
            $user->setValue('password', md5($_POST['password2'] . $user->getValue('id') . 'genesisville'));
            $pm->save($user);

            $_GET['msg'] = "Password Updated";
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
                $user = $pm->getObjectById('User', $row);
                $user->setValue('deleted', 1);
                $pm->save($user);
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            echo $e;
        }
    }

    public static function RequireLogin($url) {
        if (!self::IsAuthenticated()) {
            self::RedirectBack($url);
        }
    }

    private static function RedirectBack($url) {
        $redirect = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $redirect . 's';
        }
        $redirect .= '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if (!empty($_SERVER['QUERY_STRING']))
            $redirect .= '?' . $_SERVER['QUERY_STRING'];
        header('Location: ' . $url . '?return=' . $redirect);
    }

    public static function RequireRole($role, $url) {
        if (is_array($role)) {
            $allow = false;
            for ($i = 0; $i < count($role); $i++) {
                if (self::IsInRole($role[$i])) {
                    $allow = true;
                    break;
                }
            }
            if (!$allow) {
                self::RedirectBack($url);
            }
        } else if (!self::IsInRole($role)) {
            self::RedirectBack($url);
        }
    }

    public static function RequireType($type, $url) {
        self::RequireLogin($url);
        if (UserService::GetUser()->getValue('type') != $type) {
            self::RedirectBack($url);
        }
    }

    public static function Login($userName, $password, $redirect) {
        try {
            if (self::IsAuthenticated()) {
                self::Logout();
            }
            $pm = PersistenceManager::getConnection();
            $query = $pm->getQueryBuilder('User');
            $sql = 'select * from ' . User::GetDSN() . ' where username = ?';
            $values = array($userName);
            $users = $query->executeQuery($sql, $values, 0, 1);
            if ($users->count() > 0 && md5($password . $users[0]->getValue('id') . 'genesisville') == $users[0]->getValue('password')) {
                session_regenerate_id();
                $_SESSION['User'] = $users[0];
                self::Redirect($redirect);
                return true;
            }
        } catch (Exception $e) {
            
        }
    }

    public static function Redirect($redirect) {
        $str = '';
        if (!empty($_SERVER['QUERY_STRING']))
            $str .= '?' . $_SERVER['QUERY_STRING'];
        $pos = strpos($str, 'return=');
        if ($pos)
            header('Location: ' . substr($str, $pos + 7));
        else
            header('Location: ' . $redirect);
    }

    public static function Logout() {
        try {
            if (isset($_SESSION['User'])) {
                unset($_SESSION['User']);
            }
        } catch (Exception $e) {
            echo $e;
        }
    }

    public static function IsAuthenticated() {
        try {
            if (isset($_SESSION['User'])) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            echo $e;
        }
    }

    public static function GetUser() {
        try {
            if (isset($_SESSION['User'])) {
                return $_SESSION['User'];
            }
        } catch (Exception $e) {
            echo $e;
        }
    }

}

?>
