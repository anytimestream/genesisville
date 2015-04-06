<?php

require 'models/impl/BlobService.php';

class CardService {

    public static function GetCards() {
        try {
            $size = 50;
            $index = 1;
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            $values = array();
            $csql = "select count(*) from " . Card::GetDSN();
            $sql = "select * from " . Card::GetDSN() . " order by last_changed desc, status desc";
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Card');
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new Pagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/hotspot/cards?');
            $pagination->setPages();
            $cards = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $_GET['cards'] = $cards;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
        }
    }

    public static function DoInsert() {
        try {
            $pm = PersistenceManager::getConnection();
            $pm->beginTransaction();
            $array = explode('.', $_FILES['image']['name']);
            $rand = rand(100000, 999999) . rand(100000, 999999) . rand(100000, 999999) . '.' . $array[count($array) - 1];
            $blobService = BlobService::GetInstance();
            if ($blobService::uploadFile('uploads/' . $rand, 'image')) {
                $fh = fopen('uploads/' . $rand, 'r');
                $cards = fgets($fh);
                while (!feof($fh)) {
                    $cards = fgets($fh);
                    if ($cards != false) {
                        $cards = explode(',', str_replace("\"", "", $cards));
                        if (count($cards) == 2) {
                            $card = new Card();
                            $card->setValue('plan', $_POST['plan']);
                            $card->setValue('username', $cards[0]);
                            $card->setValue('password', $cards[1]);
                            $card->setValue('transaction_id', '_default');
                            $pm->save($card);
                            $csql = "select count(*) from " . Card::GetDSN() . " where username = ? and status = ?";
                            $query = $pm->getQueryBuilder('Card');
                            $row = $query->execute($csql, array($card->getValue('username'), 0))->fetch();
                            if($row[0] > 1){
                                throw new Exception('Card with username: '.$card->getValue('username'). ' has not been used');
                            }
                        }
                    }
                }
                fclose($fh);
                $blobService::deleteFile('uploads/' . $rand);
            }
            $pm->commit();
        } catch (Exception $e) {
            $pm->rollBack();
            $_GET['error'] = $e->getMessage();
        }
    }

}

?>
