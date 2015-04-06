<?php

class PasswordChangeRequestService {

    public static function Get() {
        try {
            $size = 50;
            $index = 1;
            if(isset($_GET['page'])){
                $index = $_GET['page'];
            }
            $values = array(0);
            $csql = "select count(*) from " . PasswordChangeRequest::GetDSN();
            $sql = "select p.id,p.password,a.account_number as userid, u.name from " . PasswordChangeRequest::GetDSN() . " as p inner join ".PrepaidUserAccountNumber::GetDSN()." as a inner Join ".PrepaidUser::GetDSN()." as u on p.user = a.prepaid_user and p.user = u.id order by p.creation_date desc";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PasswordChangeRequest');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/prepaid/password-change-requests?');
            $pagination->setPages();
            $passwordChangeRequests = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['password-change-requests'] = $passwordChangeRequests;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            echo $e;
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoInsert($user, $password) {
        $pm = null;
        try {

            $pm = PersistenceManager::getConnection();
            $passwordChangeRequest = new PasswordChangeRequest();
            $passwordChangeRequest->setValue('user', $user);
            $passwordChangeRequest->setValue('password', $password);
            $pm->save($passwordChangeRequest);
            SMSService::SendSMS(str_replace(" ", "+", "Hi, You have a pending Password Change Request"), "2348064363747", "GenesisISP", true);
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }
    
    public static function Activate() {
        $pm = null;
        try {
            $pm = PersistenceManager::getConnection();
            $pm->beginTransaction();
            $query = $pm->getQueryBuilder('PrepaidUser');
            $sql = "select u.phone,a.account_number,p.password from ".PrepaidUser::GetDSN()." as u inner join ".PrepaidUserAccountNumber::GetDSN()." as a inner join ".PasswordChangeRequest::GetDSN()." as p on u.id = a.prepaid_user and u.id = p.user where p.id = ?";
            $users = $query->executeQuery($sql, array($_GET['id']), 0, 1);
            $user = $pm->getObjectByColumn('User', 'username', $users[0]->getValue('account_number'));
            $user->setValue('password', md5($users[0]->getValue('password') . $user->getValue('id') . 'genesisville'));
            $pm->save($user);
            $pm->deleteObjectById('PasswordChangeRequest', $_GET['id']);
            $pm->commit();
            SMSService::SendPrepaidUserPasswordChange($users[0]->getValue('password'), $users[0]->getValue('account_number'), $users[0]->getValue('phone'));
            header('Location: '.CONTEXT_PATH.'/backend/prepaid/password-change-requests');
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            MailService::SendError("Password Change Request - Activate", $e->getMessage());
        }
    }

}

?>
