<?php

class PCUser extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['name'] = new Property('name', '', true, IndexType::Normal);
        $this->properties['address'] = new Property('address', '', true, IndexType::Normal);
        $this->properties['city'] = new Property('city', '', true, IndexType::Normal);
        $this->properties['phone'] = new Property('phone', '', true, IndexType::Normal);
        $this->properties['email'] = new Property('email', '', true, IndexType::Normal);

        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN() {
        return PersistenceManager::getDSN_UserName().'genesisville.pc_users';
    }
}
?>
