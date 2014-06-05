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
