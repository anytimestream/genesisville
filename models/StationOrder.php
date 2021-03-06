<?php
class StationOrder extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', 'station_order_'.uniqid(), true, IndexType::PrimaryKey);
        $this->properties['contract_value'] = new Property('contract_value', 0, true, IndexType::Normal);
        $this->properties['plan'] = new Property('plan', '', true, IndexType::Normal);
        $this->properties['phone'] = new Property('phone', '', true, IndexType::Normal);
        $this->properties['station'] = new Property('station', '', true, IndexType::Normal);
        $this->properties['token'] = new Property('token', '', true, IndexType::Normal);
        $this->properties['user'] = new Property('user', '', true, IndexType::Normal);
        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);

        //ValidationRules
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN() {
        return PersistenceManager::getDSN_UserName().'genesisville.station_orders';
    }

}
?>
