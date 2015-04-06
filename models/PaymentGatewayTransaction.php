<?php
class PaymentGatewayTransaction extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', date('dmY').  rand(10000, 99999).  rand(10000, 99999), true, IndexType::PrimaryKey);
        $this->properties['referrer'] = new Property('referrer', '', true, IndexType::Normal);
        $this->properties['method'] = new Property('method', '', true, IndexType::Normal);
        $this->properties['merchant_reference'] = new Property('merchant_reference', '', true, IndexType::Normal);
        $this->properties['response_code'] = new Property('response_code', '', true, IndexType::Normal);
        $this->properties['response_description'] = new Property('response_description', '', true, IndexType::Normal);
        $this->properties['source'] = new Property('source', '', true, IndexType::Normal);
        $this->properties['amount'] = new Property('amount', '', true, IndexType::Normal);
        $this->properties['status'] = new Property('status', '', true, IndexType::Normal);
        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);

        //ValidationRules
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN() {
        return PersistenceManager::getDSN_UserName().'genesisville.payment_gateway_transactions';
    }

}
?>
