<?php

require_once 'app/Person.php';

class PersonTest extends PHPUnit_Framework_TestCase {
    
    private $one = '{"data":[{"ucsfeduworkingdepartmentname":["ITS"],"telephonenumber":["+1 415 502-7575"],"ucsfeduprofilenodeid":["39487740"],"displayname":["Kevin Dale"],"postaladdress":["Box 0272\n1855 Folsom Street, MCB Room 401S\nSan Francisco, CA 94143"],"uid":["88834"],"ucsfeduprimarydepartmentnumber":["411112"],"ucsfeduworkingtitle":["Sr Manager, Identity Mgmt"],"mobile":["+1 415 806-8480"],"roomnumber":["401S"],"mail":["kevin.dale@ucsf.edu"],"box":["Box 0272"],"baseaddress":["1855 Folsom Street\nSan Francisco, CA 94143"],"primary":{"box":["Box 0272"],"building":["MCB"],"baseaddress":["1855 Folsom Street\nSan Francisco, CA 94143"],"postaladdress":["Box 0272\n1855 Folsom Street, MCB Room 401S\nSan Francisco, CA 94143"],"cn":["Campus"],"ucsfeduaddressprimaryflag":["true"],"roomnumber":["401S"],"telephonenumber":["+1 415 502-7575"],"ucsfedusecondarytelephonenumber":[null],"ucsfedutelephonenumberreleasecode":[null],"ucsfedusecondarytelephonenumberreleasecode":[null]},"ucsfeduprimarydepartmentname":["F_IT Identity and Access Mgt"],"departmentname":["F_IT Identity and Access Mgt"]}]}';
    private $multiple = '{"data":[{"ucsfeduworkingdepartmentname":["Departmt of Clinical Pharmacy"],"displayname":["Kevin Ohara"],"postaladdress":["Kaiser Permanente\n4867 W.Sunset Blvd\nLos Angeles, CA 90027"],"ucsfedudegree":["PharmD"],"uid":["70313"],"ucsfeduprimarydepartmentnumber":["332008"],"ucsfeduworkingtitle":["Assistant Clinical Professor"],"mail":["kevin.y.ohara@kp.org"],"box":[""],"baseaddress":["Kaiser Permanente\n4867 W.Sunset Blvd\nLos Angeles, CA 90027"],"ucsfeduprimarydepartmentname":["P_Clinical Pharmacy"],"departmentname":["P_Clinical Pharmacy"]},{"ucsfeduworkingdepartmentname":["Anatomy"],"telephonenumber":["+1 415 502-7360"],"ucsfeduprofilenodeid":["370222"],"displayname":["Peter Ohara"],"postaladdress":["Box 0444\n675 Nelson Rising Lane, Room 535-A\nSan Francisco, CA 94143"],"ucsfedudegree":["PhD"],"uid":["37027"],"ucsfeduprimarydepartmentnumber":["102004"],"ucsfeduworkingtitle":["Professor"],"roomnumber":["535-A"],"mail":["peter.ohara@ucsf.edu"],"box":["Box 0444"],"baseaddress":["675 Nelson Rising Lane\nSan Francisco, CA 94143"],"ucsfeduprimarydepartmentname":["M_Anatomy"],"departmentname":["M_Anatomy"]}]}';

    public function testParsingOne() {
        
        $records = json_decode($this->one);
        foreach ($records->data as $record) {
            $people[] = new Person($record);
        }
        
        $this->assertEquals(1, count($people));
        
        $person = $people[0];
        print_r($person);
        $this->assertEquals($person->get('ucsfeduworkingtitle'), 'Sr Manager, Identity Mgmt');
        $this->assertEquals($person->get('ucsfeduprimarydepartmentname'), 'F_IT Identity and Access Mgt');
        $this->assertEquals($person->get('mail'), 'kevin.dale@ucsf.edu');
        $this->assertEquals($person->get('postaladdress'), "Box 0272\n1855 Folsom Street, MCB Room 401S\nSan Francisco, CA 94143");
        $this->assertEquals($person->get('telephonenumber'), '+1 415 502-7575');

        $vCardText = <<<EOT
BEGIN:VCARD
VERSION:3.0
N:Kevin Dale
FN:Kevin Dale
ORG:UCSF;F_IT Identity and Access Mgt
TITLE:Sr Manager, Identity Mgmt
EMAIL:kevin.dale@ucsf.edu
TEL;Type=WORK:+1 415 502-7575
ADR;Type=POSTAL:Box 0272;1855 Folsom Street, MCB Room 401S;San Francisco, CA 94143
ADR;Type=BASE:1855 Folsom Street;San Francisco, CA 94143
END:VCARD
EOT;
        $this->assertEquals($vCardText, $person->vCard());
    }    
    
//    public function testParsing() {
//        
//        $records = json_decode($this->multiple);
//        foreach ($records->data as $record) {
//            $people[] = new Person($record);
//        }
//        
//        $this->assertEquals(2, count($people));
//        
//        $person = $people[1];
//        $this->assertEquals($person->get('ucsfeduworkingtitle'), 'Professor');
//        $this->assertEquals($person->get('ucsfeduprimarydepartmentname'), 'M_Anatomy');
//        $this->assertEquals($person->get('mail'), 'peter.ohara@ucsf.edu');
//        $this->assertEquals($person->get('postaladdress'), "Box 0444\n675 Nelson Rising Lane, Room 535-A\nSan Francisco, CA 94143");
//        $this->assertEquals($person->get('telephonenumber'), '+1 415 502-7360');
//
//        $vCardText = <<<EOT
//BEGIN:VCARD
//VERSION:3.0
//N:Peter Ohara
//FN:Peter Ohara
//ORG:UCSF;M_Anatomy
//TITLE:Professor
//EMAIL:peter.ohara@ucsf.edu
//TEL;Type=WORK:+1 415 502-7360
//ADR;Type=POSTAL:Box 0444;675 Nelson Rising Lane, Room 535-A;San Francisco, CA 94143
//ADR;Type=BASE:675 Nelson Rising Lane;San Francisco, CA 94143
//END:VCARD
//EOT;
//        $this->assertEquals($vCardText, $person->vCard());
//    }
//    
}



?>
