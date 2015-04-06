<?php
class CustomerInvoice extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['customer'] = new Property('customer', '', true, IndexType::Normal);
        $this->properties['amount'] = new Property('amount', 0, true, IndexType::Normal);
        $this->properties['tenure'] = new Property('tenure', '', true, IndexType::Normal);
        $this->properties['plan'] = new Property('plan', '', true, IndexType::Normal);
        $this->properties['expires'] = new Property('expires', '', true, IndexType::Normal);
        $this->properties['status'] = new Property('status', 0, true, IndexType::Normal);
        $this->properties['postedBy'] = new Property('postedBy', '', true, IndexType::Normal);
        $this->properties['discount'] = new Property('discount', '', true, IndexType::Normal);
        $this->properties['paymentMethod'] = new Property('paymentMethod', '', true, IndexType::Normal);
        $this->properties['creationDate'] = new Property('creationDate', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['lastChanged'] = new Property('lastChanged','',false, IndexType::Timestamp);

        //ValidationRules
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN() {
        return PersistenceManager::getDSN_UserName().'genesisville.customerpayments';
    }

}
?>
