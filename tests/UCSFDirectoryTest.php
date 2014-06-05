<?php

include_once('app/UCSFDirectory.php');

/**
 * This isn't really a complete test. Generally I'd have a local copy of the
 * data service (like a db or the UCSF Directory site) within my development
 * environment. This way I could ensure that the test data would be consistent
 * for testing. For now, this is good enough.  However, were another 'Kevin' to 
 * be added to the UCSF Directory with a name that came before 'Kevin Dale'
 * the $multi test could fail. Or if the full name of the record was no longer
 * named 'displayname', and so on.
 */
class UCSFDirectoryTest extends PHPUnit_Framework_TestCase {
    
    // This makes for a getaddrinfo failed.  It's not worth testing bad URL 
    // params as the UCSF directory service just returns an empty data set, 
    // as if it was a valid quuery that simply had no match.
    const BROKEN_DIRECTORY_URL_STR = 'https://doesnt-exist.example.com/?q=%s&json';
    
    /**
     * Test for successful, multiple queries with a single directory obj.
     */
    public function testQuery() {
        $d = new UCSFDirectory();
        
        // Check empty results
        $zero = $d->query('thisisntanyonesname');
        
        $this->assertEquals(0, count($zero));
        
        // Check single result
        $single = $d->query('Kevin Dale');
        
        $this->assertEquals('Kevin Dale', $single[0]->get('displayname'));

        $this->assertEquals(1, count($single));        
        
        // Check multiple results -- this will probably fail when the right
        // record is added to the directory... it already has once.
        $multi = $d->query('Kevin');
        
        $this->assertEquals(117, count($multi));
        
        $this->assertEquals('Kevin Dale', $multi[24]->get('displayname'));
        
        
    }
    
    /**
     * Test for graceful failure to connect to directory service 
     */
    public function testQueryFailure() {
        $failed = FALSE;
        
        try {
            $d = new UCSFDirectory(self::BROKEN_DIRECTORY_URL_STR);
            $d->query('Kevin');
        } catch (Exception $e) {
            $failed = TRUE;
        }
        
        $this->assertTrue($failed);
    }
    
}



?>
