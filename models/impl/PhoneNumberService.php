<?php

class PhoneNumberService {

    public static function GetPhoneNumbers() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array();
            $search = "";
            $url_search = "";
            $csql = "select count(*) from " . PhoneNumber::GetDSN();
            $sql = "select * from " . PhoneNumber::GetDSN();

            if (isset($_GET['type']) && $_GET['type'] != 'all') {
                $search = " where related_to = ?";
                $url_search = "type=" . $_GET['type'];
                $values[] = urldecode($_GET['type']);
            }

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PhoneNumber');
            $row = $query->execute($csql . $search, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/management/phone-numbers?' . $url_search . '&');
            $pagination->setPages();
            $phones = $query->executeQuery($sql . $search . " order by last_changed desc", $values, (($index - 1) * $size), $size);
            $_GET['phones'] = $phones;
            $_GET['url_search'] = $url_search;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function GetDistinctTypes() {
        $pm = null;
        try {
            $sql = "select distinct related_to as name from " . PhoneNumber::GetDSN();
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PhoneNumber');
            $phones = $query->executeQuery($sql, array(), 0, 10000);
            $_GET['types'] = $phones;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function DoInsert($number, $type, $related_to) {
        $pm = null;
        try {
            $pm = PersistenceManager::getConnection();
            $sql = "select * from " . PhoneNumber::GetDSN() . " where phone = ? and type = ? and related_to = ?";
            $query = $pm->getQueryBuilder('PhoneNumber');
            $phones = $query->executeQuery($sql, array($number, $type, $related_to), 0, 1);
            $phone = new PhoneNumber();
            if ($phones->count() > 0) {
                $phone = $phones[0];
                $phone->setValue('revision', $phone->getValue('revision') + 1);
            } else {
                $phone->setValue('phone', $number);
                $phone->setValue('type', $type);
                $phone->setValue('related_to', $related_to);
            }
            $pm->save($phone);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function DoDownload() {
        try {
            $values = array();
            $search = "";
            $url_search = "";
            $sql = "select phone from " . PhoneNumber::GetDSN();

            if (isset($_GET['type']) && $_GET['type'] != 'all') {
                $search = " where related_to = ?";
                $url_search = "type=" . $_GET['type'];
                $values[] = urldecode($_GET['type']);
            }

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('PhoneNumber');
            $phones = $query->executeQuery($sql . $search . " order by last_changed desc", $values, 0, 100000);
            header('Content-Type: text');
            header("Content-Disposition: attachment; filename=Phone-numbers.txt");
            for ($i = 0; $i < $phones->count(); $i++) {
                echo $phones[$i]->getValue('phone') . "\r\n";
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

}

?>
