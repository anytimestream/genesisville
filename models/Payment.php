<?php
class Payment extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['amount'] = new Property('amount', '', true, IndexType::Normal);
        $this->properties['transaction_id'] = new Property('transaction_id', 0, true, IndexType::Normal);
        $this->properties['method'] = new Property('method', '', true, IndexType::Normal);
        $this->properties['related_to'] = new Property('related_to', '', true, IndexType::Normal);
        $this->properties['type'] = new Property('type', '', true, IndexType::Normal);
        $this->properties['pos_ref'] = new Property('pos_ref', '', true, IndexType::Normal);
        $this->properties['status'] = new Property('status', 0, true, IndexType::Normal);
        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);

        //ValidationRules
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN() {
        return PersistenceManager::getDSN_UserName().'genesisville.payments';
    }

}
?>
