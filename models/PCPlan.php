<?php
class PCPlan extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['amount'] = new Property('amount', 0, true, IndexType::Normal);
        $this->properties['description'] = new Property('description', '', true, IndexType::Normal);
        $this->properties['name'] = new Property('name', '', true, IndexType::Normal);
        $this->properties['deleted'] = new Property('deleted', 0, true, IndexType::Normal);
        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);

        //ValidationRules
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN() {
        return PersistenceManager::getDSN_UserName().'genesisville.pc_plans';
    }

}
?>
