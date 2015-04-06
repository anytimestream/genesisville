<?php

class SMSMessageService {

    public static function GetMessages() {
        try {
            $size = 50;
            $index = 1;
            if(isset($_GET['page'])){
                $index = $_GET['page'];
            }
            $values = array(0);
            $csql = "select count(*) from " . SMSMessage::GetDSN();
            $sql = "select * from " . SMSMessage::GetDSN() . " order by type";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('SMSMessage');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/management/sms-messages?');
            $pagination->setPages();
            $messages = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['messages'] = $messages;
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

            $messages = new PersistableListObject(null, null);

            foreach ($data as $id => $row) {

                $assertProperties->addProperty($row, 'message');
                $assertProperties->addProperty($row, 'sender');
                $assertProperties->assert();

                $message = $pm->getObjectById('SMSMessage', $id);

                $message->setValue('message', $row['message']);
                $message->setValue('sender', $row['sender']);

                $pm->save($message);

                $messages->add($message);
            }

            $_GET['messages'] = $messages;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

}

?>
