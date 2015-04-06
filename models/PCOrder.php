<?php
class PCOrder extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', 'prepaid_order_'.uniqid(), true, IndexType::PrimaryKey);
        $this->properties['user'] = new Property('user', '', true, IndexType::Normal);
        $this->properties['tenure'] = new Property('tenure', '', true, IndexType::Normal);
        $this->properties['plan'] = new Property('plan', '', true, IndexType::Normal);
        $this->properties['expires'] = new Property('expires', '', true, IndexType::Normal);
        $this->properties['state'] = new Property('state', 0, true, IndexType::Normal);
        $this->properties['status'] = new Property('status', 0, true, IndexType::Normal);
        $this->properties['amount'] = new Property('amount', 0, true, IndexType::Normal);
        $this->properties['posted_by'] = new Property('posted_by', '', true, IndexType::Normal);
        $this->properties['remarks'] = new Property('remarks', '', true, IndexType::Normal);
        $this->properties['transaction_id'] = new Property('transaction_id', '', true, IndexType::Normal);
        $this->properties['method'] = new Property('method', '', true, IndexType::Normal);
        $this->properties['station'] = new Property('station', '', true, IndexType::Normal);
        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);

        //ValidationRules
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN() {
        return PersistenceManager::getDSN_UserName().'genesisville.pc_orders';
    }

}
?>
