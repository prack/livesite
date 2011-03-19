_This project is very much a work in progress. Feedback is welcome._

Sandbox
=======

Sandbox is a simple demonstration of how to build your domain as a middleware app
stack using [php-rack](http://github.com/prack/php-rack).


Dependencies
============

Sandbox is built on top of [php-rack](http://github.com/prack/php-rack) ("Prack"), which
is in turn built on top of a "Rubification" library called 
[php-ruby](http://github.com/prack/php-rb "Prb Homepage") ("Prb").

Consequently, you'll need to clone both of these into the 'lib' subdirectory.

For more detailed instructions, see the "Getting Started" section below.


Classes
=======

Sandbox comes with just a few middleware applications, which are used in the domain
created in rackup.php. here they are:

* Showenv: appends a var_dump'ed representation of the environment on its way
  out the middleware app stack.
* Admin: Prints a welcome message to the logged in user.
* Public: Prints a generit message to a visitor of the site to a non-admin section.


Getting started
===============

To view the sandbox in action, you'll need:

1. A valid apache instance with php 5.2+ installed.
2. Knowledge on configuring a host, as there are (unfortunately) a few required
   apache configuration directives.
3. Clones of the support libraries in the 'lib/' subdirectory of your clone.
   You may consider using git submodules for this; or, if you're in a hurry, just clone
   the repos into your repository directly.

The file structure should look like this:

	- your clone
	\_lib
	 \_sandbox  (comes with the clone)
	 \_php-rack (must be provided to the repository)
	 \_php-rb   (must be provided to the repository)

And apache must serve this cloned directory configured thusly:

	RewriteEngine On
	DirectoryIndex rackup.php
	SetEnvIf Request_URI (.*) X_PRACK_PATHINFO=$1
	RewriteCond %{REQUEST_URI} .+
	RewriteRule .+ rackup.php [L]

This is due to the fact that, from Apache's file-server point of view, the "PATH\_INFO"
environment variable is the file being served (naturally a 404 if the URL doesn't map to a file);
however, in the application-server sense which php-rack was built to emulate, PATH\_INFO
represents a path _responded to by code_. This directive rewrites the original
request URI, setting X\_PRACK_PATHINFO to the original value, which prack uses to create
an environment. It then hands this environment to the middleware app stack it runs.

A simple, quick-and-dirty setup might look like this:

	$ cd ~/PHPDev
	$ git clone git@github.com:prack/sandbox.git sandbox
	$ cd sandbox
	$ git clone git@github.com:prack/php-rack.git lib/php-rack
	$ git clone git@github.com:prack/php-rb.git lib/php-rb
	$ cp /path/to/httpd.conf /path/to/httpd.conf.bak
	$ vi /path/to/httpd.conf
	// Edit your host configuration to include the above directives.
	$ sudo apachectl restart

Barring any errors in your host configuration, your sandbox should run immediately.

Play with it, checkout the other middleware included in php-rack. Add to the stack, delete
from it... the world is your oyster. :)


Acknowledgments
===============

Thanks to the Ruby Rack team for all their hard work on Rack, and thanks to the Python folks
who dreamed up WSGI. And thanks to Matz for making such an amazing language.
