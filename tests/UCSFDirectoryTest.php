<?php

include_once('app/UCSFDirectory.php');

/**
 * This isn't really a well designed test. Generally I'd have a local copy of the
 * data service (like a db or the UCSF Directory site) within my development
 * environment. With this I could ensure that the test data would be consistent
 * for testing. For now, this is good enough.  
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
        
        // Check multiple results
        $multi = $d->query('Kevin');
        $this->assertGreaterThan(1, count($multi));
        foreach ($multi as $record) {
            // Yeah it might match on something else... but how many departments
            // are named 'Kevin'?
            $this->assertContains('Kevin', $record->get('displayname'));
        }
        
        
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
