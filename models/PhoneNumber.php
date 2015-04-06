<?php
class PhoneNumber extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['phone'] = new Property('phone', '', true, IndexType::Normal);
        $this->properties['revision'] = new Property('revision', 1, true, IndexType::Normal);
        $this->properties['type'] = new Property('type', '', true, IndexType::Normal);
        $this->properties['related_to'] = new Property('related_to', '', true, IndexType::Normal);
        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);

        //ValidationRules
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN() {
        return PersistenceManager::getDSN_UserName().'genesisville.phone_numbers';
    }

}
?>
