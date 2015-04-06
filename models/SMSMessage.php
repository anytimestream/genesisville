<?php

class SMSMessage extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['type'] = new Property('type', '', true, IndexType::Normal);
        $this->properties['message'] = new Property('message', '', true, IndexType::Normal);
        $this->properties['sender'] = new Property('sender', '', true, IndexType::Normal);
        $this->properties['creationDate'] = new Property('creationDate', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['lastChanged'] = new Property('lastChanged','',false, IndexType::Timestamp);
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN(){
        return PersistenceManager::getDSN_UserName().'genesisville.smsmessages';
    }
}

?>
