<?php

require_once 'app/Person.php';

class PersonTest extends PHPUnit_Framework_TestCase {
    
    private $multiple = '{"data":[{"ucsfeduworkingdepartmentname":["Departmt of Clinical Pharmacy"],"displayname":["Kevin Ohara"],"postaladdress":["Kaiser Permanente\n4867 W.Sunset Blvd\nLos Angeles, CA 90027"],"ucsfedudegree":["PharmD"],"uid":["70313"],"ucsfeduprimarydepartmentnumber":["332008"],"ucsfeduworkingtitle":["Assistant Clinical Professor"],"mail":["kevin.y.ohara@kp.org"],"box":[""],"baseaddress":["Kaiser Permanente\n4867 W.Sunset Blvd\nLos Angeles, CA 90027"],"ucsfeduprimarydepartmentname":["P_Clinical Pharmacy"],"departmentname":["P_Clinical Pharmacy"]},{"ucsfeduworkingdepartmentname":["Anatomy"],"telephonenumber":["+1 415 502-7360"],"ucsfeduprofilenodeid":["370222"],"displayname":["Peter Ohara"],"postaladdress":["Box 0444\n675 Nelson Rising Lane, Room 535-A\nSan Francisco, CA 94143"],"ucsfedudegree":["PhD"],"uid":["37027"],"ucsfeduprimarydepartmentnumber":["102004"],"ucsfeduworkingtitle":["Professor"],"roomnumber":["535-A"],"mail":["peter.ohara@ucsf.edu"],"box":["Box 0444"],"baseaddress":["675 Nelson Rising Lane\nSan Francisco, CA 94143"],"ucsfeduprimarydepartmentname":["M_Anatomy"],"departmentname":["M_Anatomy"]}]}';

    public function testParsing() {
        
        $records = json_decode($this->multiple);
        foreach ($records->data as $record) {
            $people[] = new Person($record);
        }
        
        $this->assertEquals(2, count($people));
        
        $person = $people[1];
        $this->assertEquals($person->get('ucsfeduworkingtitle'), 'Professor');
        $this->assertEquals($person->get('ucsfeduprimarydepartmentname'), 'M_Anatomy');
        $this->assertEquals($person->get('mail'), 'peter.ohara@ucsf.edu');
        $this->assertEquals($person->get('postaladdress'), "Box 0444\n675 Nelson Rising Lane, Room 535-A\nSan Francisco, CA 94143");
        $this->assertEquals($person->get('telephonenumber'), '+1 415 502-7360');

        $vCardText = <<<EOT
BEGIN:VCARD
VERSION:3.0
N:Peter Ohara
FN:Peter Ohara
ORG:UCSF;M_Anatomy
TITLE:Professor
EMAIL:peter.ohara@ucsf.edu
TEL;Type=WORK:+1 415 502-7360
ADR;Type=POSTAL:Box 0444;675 Nelson Rising Lane, Room 535-A;San Francisco, CA 94143
ADR;Type=BASE:675 Nelson Rising Lane;San Francisco, CA 94143
END:VCARD
EOT;
        $this->assertEquals($vCardText, $person->vCard());
    }
    
}



?>
