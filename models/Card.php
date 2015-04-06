<?php
class Card extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['plan'] = new Property('plan', '', true, IndexType::Normal);
        $this->properties['username'] = new Property('username', '', true, IndexType::Normal);
        $this->properties['password'] = new Property('password', '', true, IndexType::Normal);
        $this->properties['status'] = new Property('status', '', true, IndexType::Normal);
        $this->properties['transaction_id'] = new Property('transaction_id', '', true, IndexType::Normal);
        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);

        //ValidationRules
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN() {
        return PersistenceManager::getDSN_UserName().'genesisville.cards';
    }

}
?>
