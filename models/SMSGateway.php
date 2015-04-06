<?php
class SMSGateway extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['name'] = new Property('name', '', true, IndexType::Normal);
        $this->properties['isDefault'] = new Property('isDefault', '', true, IndexType::Normal);
        $this->properties['creationDate'] = new Property('creationDate', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['lastChanged'] = new Property('lastChanged','',false, IndexType::Timestamp);

        //ValidationRules
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN() {
        return PersistenceManager::getDSN_UserName().'genesisville.smsgateways';
    }

}
?>
