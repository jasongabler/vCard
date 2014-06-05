<?php

/**
 * A Person represents a record from the UCSF directory at directory.ucsf.edu 
 */
class Person {
    
    private $record;    // See the constructor for a description
    private $keys;      // of $record and $keys.
    
    /**
     * Construct a Person from a UCSF directory record
     * 
     * @param mixed $record An object from a decoded UCSF directory JSON record
     */
    public function __construct($record) {
        $this->record = new stdClass();
        $this->flattenRecord('', $record);
    }
    
    /*
     * The properties within a UCSF directory record are generally a key/value
     * pair wrapped in a single member numeric array.  When multiple records are retrieved
     * from the directory this appears to always be the case. However, when a single
     * record is retrieved, various bits of contact information are repeated
     * within an inner class pointed to by a member named 'primary'.  
     * 
     * flattenRecord() brings all properties of nested objects up to the same level
     * within Person->record, forming property names based upon the nested names.
     * For example, $record->uid becomes $this->record->uid since $record->uid was
     * not an object. However, since $record->primary is an object, $record->primary->cn
     * becomes $this->record->primary_cn.
     */
    private function flattenRecord($basename, $obj) {
        foreach (get_object_vars($obj) as $key => $value) {
            $compoundKey = empty($basename) ? $key : $basename.'_'.$key;
            if (is_array($value)) {
                $this->keys[] = $compoundKey;
                $this->record->$compoundKey = $value[0];
            } else {
                $this->flattenRecord($compoundKey, $value);
                unset($this->record->$key);
            }
        }
        
        
    }
     
    /**
     * The number and variety of $record fields are unknown quantities. In light
     * of that, get() provides a generic getter that prevents runtime errors
     * and keeps properties private and OO.
     * 
     * @param string The name of a UCSF directory property.
     * @return string The value of the property, or NULL if the property doesn't exist.
     */
    public function get($key) {
        $value = null;
        
        if (in_array($key, $this->keys)) {
            $value = $this->record->$key;
        }
        return $value;
    }

    /**
     * Get the names of record properties.
     * @return array An array of property key names.
     */
    public function keys() {
        return $this->keys;
    }
    
    /**
     * Generate a vCard from a UCSF directory record. The output from vCard()
     * import just fine for GMail and Macintosh's built-in contact management.
     * 
     * @return string An importable vCard text document
     */
    public function vCard() {
        $content  = "BEGIN:VCARD\nVERSION:3.0\n";
        $content .= (empty($this->record->displayname)) ? "" : "N:".$this->record->displayname."\n";
        $content .= (empty($this->record->displayname)) ? "" : "FN:".$this->record->displayname."\n";
        $content .= (empty($this->record->departmentname)) ? "" : "ORG:UCSF;".$this->record->departmentname."\n";
        $content .= (empty($this->record->ucsfeduworkingtitle)) ? "" : "TITLE:".$this->record->ucsfeduworkingtitle."\n";
        $content .= (empty($this->record->mail)) ? "" : "EMAIL:".$this->record->mail."\n";
        $content .= (empty($this->record->telephonenumber)) ? "" : "TEL;Type=WORK:".$this->record->telephonenumber."\n";
        $content .= (empty($this->record->postaladdress)) ? "" : "ADR;Type=POSTAL:".preg_replace('/[\r\n]+/', ';', $this->record->postaladdress)."\n";
        $content .= (empty($this->record->baseaddress)) ? "" : "ADR;Type=BASE:".preg_replace('/[\r\n]+/', ';', $this->record->baseaddress)."\n";
        $content .= "END:VCARD";  
        return $content;
    }
    
}


?>
