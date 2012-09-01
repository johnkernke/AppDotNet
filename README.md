AppDotNet
=========

Provides access to the app.net API (https://github.com/appdotnet/api-spec) using
PHP.

The API is broken up into different classes for the main sections (eg. Users or
Posts).

This library is slightly based on https://github.com/jdolitsky/AppDotNetPHP for
ideas and getting my head around how the API works.

Usage
=====

First you will need to rename config.php.sample to config.php, and set the
variables inside that to those provided by app.net when creating your app.

An example website has been provided for how to use the library.

Pull Requests / Issues
======================

If you would like to contribute to the project, feel free to send pull requests,
I don't really know what I am doing with them but should be able to work it out!

If you find any bugs with the project or any suggestions, submit either a pull
request or an issue on github.

A note for if you are doing a pull request, I am using the PHP PSR standards, so
if you would be able to do that as well it would be nice (using camel case
methods and underscores for variables).

Notes
=====

Currently some classes are not implemented, this is because I just got the
library working and app.net hasn't made those endpoints live yet, they will be
implemented soon but for now they are placeholders (Filters, Streams,
Subscriptions).

License
=======

Not really sure on licenses, as long as you credit me if you use the library in
some way (just on one page somewhere) and send me a message so I can have a
look.