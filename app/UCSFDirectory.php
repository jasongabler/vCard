<?php

require_once 'Person.php';

/**
 * A class to represent the directory at ucsf.directory.edu
 */
class UCSFDirectory {

    const DIRECTORY_URL_STR = 'https://directory.ucsf.edu/?q=%s&json';
    
    private $urlString;
    
    public function __construct($url = NULL) {
        if (empty($url)) {
            $this->urlString = self::DIRECTORY_URL_STR;
        } else {
            $this->urlString = $url;
        }
    }

    /**
     * Send a search query to the directory service web page, asking for JSON
     * results and return the data as PHP data structures.
     * 
     * @param string $filter The search filter
     * @return array The result of the search as an array of Persons
     */
    public function query($filter) {
        $people = array();
        
        if (!empty($filter)) {
            // Retrieve records from the directory
            $url = sprintf($this->urlString, urlencode($filter));
            $records_json = file_get_contents($url);
            if ($records_json === FALSE) {
                throw new Exception('Unable to contact directory service.');
            }
            // Convert records to People objects
            $records = json_decode($records_json);
            foreach ($records->data as $record) {
                $people[] = new Person($record);
            }
        }
        
        return $people;
    }

}

function d($str, $ret = FALSE, $die = FALSE) {
    $output = print_r($str, TRUE);
    if ($ret) {
        return $output;
    }
    print '<div class="debug"><pre>' . $output . '</pre></div>';
    if ($die) {
        exit();
    }
}


 
?>
