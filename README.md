vCard
=====

Creating vCards from directory.ucsf.edu


How To Install
======================================

1. Clone this repository.

 $ git clone https://github.com/jasongabler/vCard.git

 The local repository will be created in a directory named "vCard"
 within the current working directory.

2. PHPUnit tests can be run from within the "vCard" directory, above
the "app" and "tests" directories.

 $ phpunit tests

3. Get it up on the Web

 All that's needed are the 3 files within vCard/app.  As long as
 you have a working web server PHP module that automatically reads index.php
 files, you can point a browser to the "app" directory and the search
 page will render.  The only requirement, as currently developed,
 is that the three files remain within the current directory.

Under The Hood
======================================

When the form inside index.php is submitted, the filter field is used to construct a URL for retrieving JSON data from directory.ucsf.edu. A cURL call is made to that URL and the JSON response is decoded. The resulting data structures from the JSON are futher massaged and placed into "Person" objects for easier handling and printing to the screen.

For easier reading, initially the result set is only displayed with the record's full name ("displayname") field, along with two buttons.  The button on the left opens and closes the record for viewing.  The button on the right initiates the downloading of a vCard populated with some subset of the record's details. The record details, as displayed, are labeled with the raw property names as they come from the directory service.

Each vCard is prefabricated upon page load and hidden within the DOM container that holds its analogous, displayable version of the record.  So, instead of going back to the directory.ucsf.edu service, the vCard button triggers some Javascript which grabs that prefaricated vCard data and then turns it into a file stream. The browser sees the stream as a downloadable vCard file. The resulting vCard files have been tested to successfully load into GMail and OSX's native "Contact" application.
