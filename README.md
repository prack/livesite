_Prack is maturing rapidly, and needs early adopters. It is very well-tested._

Livesite
========

Livesite is a simple demonstration of how to build your domain as a middleware app
stack using [php-rack](http://github.com/prack/php-rack).

You can [see it in action](http://php-rack.info/livesite) if you like.

Dependencies
============

Livesite is built on top of [php-rack](http://github.com/prack/php-rack) ("Prack"), which
is in turn built on top of a primitive-wrapper library called 
[php-rb](http://github.com/prack/php-rb "Prb Homepage") ("Prb").

Consequently, you'll need to clone both of these into the <tt>lib</tt> subdirectory.

Other than that, all you'll need to know about Prb is its <tt>Logger</tt>.

Code
====

Livesite comes with just a few middleware applications, which are used in the domain
created in rackup.php. here they are:

* <tt>Admin</tt>: Responds to admin site requests.
* <tt>Public</tt>: A "public" area for the php-rack-driven site.
* <tt>ShowEnv</tt>: Displays environment in response, if appropriate.
* <tt>ShowHeaders</tt>: Displays response header information in response, if appropriate.
* <tt>ShowRuntimes</tt>: Displays runtime information in response, if appropriate.
* <tt>Thrower</tt>: Throws an exception.

Getting started
===============

To view the livesite in action, you'll need a valid apache instance with php 5.2+ installed.

Apache must serve this cloned code in a (virtual) host configured thusly:

	DirectoryIndex rackup.php
	DirectorySlash Off
	RewriteEngine On
	RewriteCond %{REQUEST_URI} .+
	RewriteRule .+ rackup.php [L]

This is due to the fact that, from Apache's file-server point of view, the <tt>PATH\_INFO</tt>
environment variable is the file being served (naturally a 404 if the URL doesn't map to a file);
however, in the application-server paradigm (that is, for Prack), <tt>PATH\_INFO</tt>
represents a path _responded to by code_. This directive rewrites the original request URI,
setting <tt>REDIRECT_URL</tt> to the original value, which Prack uses while creating the
request environment. It then hands this environment to the middleware app stack it runs.

The <tt>DirectorySlash Off</tt> directive suppresses Apache's redirecting from an existing
subdirectory to the same path, with a slash tacked onto the end. This is unnecessary with Prack.

A simple, quick-and-dirty setup might look like this:

	$ cd ~/PHPDev
	$ git clone git@github.com:prack/livesite.git livesite
	$ cd livesite
	$ git submodules update --init
	$ cp /path/to/httpd.conf /path/to/httpd.conf.bak
	$ vi /path/to/httpd.conf
	// Edit your host configuration to include the above directives.
	$ sudo apachectl restart

Barring any errors in your host configuration, your livesite should run immediately.


Domain
------

The website, when running, exposes the following applications at domain-level:

* public website, at:                       <br /><pre>/</pre>
* admin app (l: (anything), p: secret), at: <br /><pre>/admin</pre>
* static asset server, at:                  <br /><pre>/static</pre>
* pretty exception handler, at:             <br /><pre>/thrower</pre>

To understand how Prack is working, inspect the displayed "environment information" closely!
Also, look at the stack built in rackup.php. Change it, too, whatever you want. Prack comes
with some perfectly useable middleware.


Acknowledgments
===============

Thanks to the Ruby Rack team for all their hard work on Rack. :) It's hard porting it to PHP!