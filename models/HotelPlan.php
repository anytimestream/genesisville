<?php
class HotelPlan extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['hotel'] = new Property('hotel', '', true, IndexType::Normal);
        $this->properties['plan'] = new Property('plan', '', true, IndexType::Normal);
        $this->properties['point'] = new Property('point', 0, true, IndexType::Normal);
        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);

        //ValidationRules
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN() {
        return PersistenceManager::getDSN_UserName().'genesisville.hotel_plans';
    }

}
?>
