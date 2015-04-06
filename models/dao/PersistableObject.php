<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Functions.php';

class PersistableObject {

    protected $properties = array();
    public $IsDirty = false;
    public $IsNew = true;
    public $IsDeleted = false;
    protected $ValidationRules;

    public function get($row) {
        foreach ($row as $property => $value) {
            if (isset($this->properties[$property])) {
                $this->properties[$property]->setValue($value);
            } else {
                $this->properties[$property] = new Property($property, $value, false, IndexType::Normal);
            }
        }
        $this->markOld();
    }

    public function markOld() {
        $this->IsDeleted = false;
        $this->IsDirty = false;
        $this->IsNew = false;
    }

    public function getValue($property) {
        if (!isset($this->properties[$property])) {
            throw new Exception('Unknown Property: ' . $property);
        }
        return $this->properties[$property]->getValue();
    }

    public function setValue($property, $value) {
        if (!isset($this->properties[$property])) {
            throw new Exception('Unknown Property: ' . $property);
        }
        if ($this->properties[$property]->getValue() != $value) {
            $this->properties[$property]->setValue($value);
            $this->IsDirty = true;
        }
    }

    public function getProperties() {
        return $this->properties;
    }

    public static function GetPropertyKeys($class_name) {
        $keys = array();
        $instance = new $class_name;
        $properties = $instance->getProperties();
        foreach ($properties as $key => $value) {
            $keys[] = $key;
        }
        return $keys;
    }

    public function getPrimaryKey() {
        foreach ($this->properties as $property) {
            if ($property->IndexType == IndexType::PrimaryKey) {
                return $property;
            }
        }
    }

    public function getTimestamp() {
        foreach ($this->properties as $property) {
            if ($property->IndexType == IndexType::Timestamp) {
                return $property;
            }
        }
    }

    public function CheckValidationRules($pm) {
        if ($this->ValidationRules != null)
            $this->ValidationRules->validate($pm, $this);
    }

    public static function CanCreate() {
        return true;
    }

    public static function CanGet() {
        return true;
    }

    public static function CanDelete() {
        return true;
    }

    public static function CanUpdate() {
        return true;
    }

}

class Property {

    private $name;
    private $value;
    public $IsSavable = true;
    public $IndexType = IndexType::Normal;

    public function getName() {
        return $this->name;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function __construct($name, $value, $IsSavable, $indexType) {
        $this->name = $name;
        $this->value = $value;
        $this->IsSavable = $IsSavable;
        $this->IndexType = $indexType;
    }

}

class IndexType {

    const PrimaryKey = 'PrimaryKey';
    const Normal = 'Normal';
    const Timestamp = 'Timestamp';

}

abstract class ListObject implements ArrayAccess {

    public $elements;

    public function offsetExists($offset) {
        return isset($this->elements[$offset]);
    }

    public function offsetGet($offset) {
        return $this->elements[$offset];
    }

    public function offsetSet($offset, $value) {
        return $this->elements[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->elements[$offset]);
    }

    public function count() {
        return count($this->elements);
    }

    public function add($value) {
        return $this->offsetSet($this->count(), $value);
    }

}

class PersistableListObject extends ListObject {

    public function __construct($st, $classname) {
        if ($st != null) {
            while (($row = $st->fetch())) {
                $value = new $classname;
                $value->get($row);
                $this->add($value);
            }
        }
    }

}

abstract class ValidationRule {

    public abstract function validate($pm, $object);
}

class ValidationRules extends ListObject {

    public function validate($pm, $object) {
        for ($i = 0; $i < $this->count(); $i++) {
            $this->elements[$i]->validate($pm, $object);
        }
    }

}

class StringValidationRule extends ValidationRule {

    protected $property;
    private $min;
    private $max;
    protected $error;

    function __construct($property, $min, $max, $error) {
        $this->property = $property;
        $this->min = $min;
        $this->max = $max;
        $this->error = $error;
    }

    public function validate($pm, $object) {
        $value = $object->getValue($this->property);
        if (strlen($value) < $this->min || strlen($value) > $this->max)
            throw new Exception($this->error);
    }

}

class NumberValidationRule extends ValidationRule {

    protected $property;
    private $min;
    private $max;
    protected $error;

    function __construct($property, $min, $max, $error) {
        $this->property = $property;
        $this->min = $min;
        $this->max = $max;
        $this->error = $error;
    }

    public function validate($pm, $object) {
        $value = $object->getValue($this->property);
        if (!is_numeric($value))
            throw new Exception($this->error);
        if (strlen($value) < $this->min || strlen($value) > $this->max)
            throw new Exception($this->error);
    }

}

class EmailValidationRule extends StringValidationRule {

    public function __construct($property, $min, $max, $error) {
        parent::__construct($property, $min, $max, $error);
    }

    public function validate($pm, $object) {
        parent::validate($pm, $object);
        if (!preg_match('/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i', $object->getValue($this->property)))
            throw new Exception($this->error);
    }

}

class SqlValidationRule extends ValidationRule {

    private $sql;
    private $properties;
    private $boolean;
    private $error;

    function __construct($sql, $properties, $boolean, $error) {
        $this->sql = $sql;
        $this->properties = $properties;
        $this->error = $error;
        $this->boolean = $boolean;
    }

    public function validate($pm, $object) {
        $v = array();
        for ($i = 0; $i < count($this->properties); $i++) {
            $v[$i] = $object->getValue($this->properties[$i]);
        }
        $query = $pm->getQueryBuilder(get_class($object));
        $st = $query->execute($this->sql, $v);

        if (($row = $st->fetch()) != $this->boolean)
            throw new Exception($this->error);
    }

}

?>
