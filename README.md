vCard
=====

Creating vCards from directory.ucsf.edu


How To Install
======================================

1. Clone this repository.

 $ git clone https://github.com/jasongabler/vCard.git

 The local repository will be created in a directory named "vCard"
 within the current working directory.

2. PHPUnit tests should be run from within the "vCard" directory, above
the "app" and "tests" directories.

 $ phpunit tests

3. Get it up on the Web

 All that's needed are the 3 files within vCard/app.  As long as
 you have a working PHP module that automatically reads index.php
 files, you can point a browser to the "app" directory and the search
 page will render.  The only requirement, as currently developed,
 is that the three files remain within the current directory.

Under The Hood
======================================

When the form inside index.php is submitted, the filter text is used to create a URL which performs a directory search at directory.ucsf.edu.  The URL is constructed to require the site to return directory records as JSON.  The JSON is parsed and the resulting data structures are futher massaged for easier printing. 

The printing of results handles single and multiple results. For easy listing, initially results are only listed with the name of the person on the record, with buttons for opening the display of an entire record and another button to download a vCard based upon a subset of properties within that directory record. The displayed records are labeled with the raw propery names which come from the directory service.

Each vCard is prefabricated upon page load and hidden within the DOM container that holds the display version of a given record.  Instead of going back to the directory server again, the vCard button triggers the running of Javascript which gets the vCard data related to the button, and turns it into a file stream which the browser sees as a downloadable vCard file. The resulting vCard file has been tested to successfully load in GMail and OSX's "Contact" application.

