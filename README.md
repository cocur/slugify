cocur/slugify
=============

> Converts a string into a slug.

[![Build Status](https://travis-ci.org/cocur/slugify.png?branch=master)](https://travis-ci.org/cocur/slugify)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/cocur/slugify/badges/quality-score.png?s=6dc4ff1137d4405f75be9e98c74b1b70fcfdffaa)](https://scrutinizer-ci.com/g/cocur/slugify/)
[![Code Coverage](https://scrutinizer-ci.com/g/cocur/slugify/badges/coverage.png?s=27306b142814efca5c7a99984d01a073e453309f)](https://scrutinizer-ci.com/g/cocur/slugify/)
[![Latest Stable Version](https://poser.pugx.org/cocur/slugify/v/stable.png)](https://packagist.org/packages/cocur/slugify)
[![Total Downloads](https://poser.pugx.org/cocur/slugify/downloads.png)](https://packagist.org/packages/cocur/slugify)


Installation
------------

You can install cocur/slugify through [Composer](https://getcomposer.org):

```shell
$ composer require cocur/slugify:@stable
```

*In a production environment you should replace `@stable` with the [version](https://github.com/cocur/slugify/releases) you want to use.*


Requirements
------------

`cocur/slugify` has no external dependencies (except PHPUnit for running the tests) and provides compatability with PHP >= 5.3.3, including PHP 5.6 and [HHVM](http://hhvm.com).


Usage
-----

Generate a slug:

```php
use Cocur\Slugify\Slugify;

$slugify = new Slugify();
echo $slugify->slugify('Hello World!'); // hello-world
```

You can also change the seperator used by `Slugify`:

```php
echo $slugify->slugify('Hello World!', '_'); // hello_world
```

The library also contains `Cocur\Slugify\SlugifyInterface`. Use this interface whenever you reference a type in your code.


Changelog
---------

### Version 0.5 (28 March 2014)

- #21 Added support for greek characters (by [Michel Petit](https://github.com/malenkiki))
- #20 Fixed rule for cyrillic letter D (by [Marchenko Alexandr](https://github.com/cocur/slugify/pull/20))
- Add missing `$separator` parameter to `SlugifyInterface`

### Version 0.4.1 (9 March 2014)

- #19 Adds soft sign rule (by [Marchenko Alexandr](https://github.com/mac2000))

### Version 0.4 (17 January 2014)

Nearly completely rewritten code, removes `iconv` support because the underlying library is broken. The code is now better and faster. Many thanks to [Marchenko Alexandr](http://mac-blog.org.ua).

### Version 0.3 (12 January 2014)

- #11 PSR-4 compatible (by [mac2000](https://github.com/mac2000))
- #13 Added editorconfig (by [mac2000](https://github.com/mac2000))
- #14 Return empty slug when input is empty and removed unused parameter (by [mac2000](https://github.com/mac2000))


Authors
-------

- [Florian Eckerstorfer](http://florian.ec) ([Twitter](http://twitter.com/Florian_), [App.net](http://app.net/florian))
- [Ivo Bathke](https://github.com/ivoba)
- [Marchenko Alexandr](http://mac-blog.org.ua)
- And some [great contributors](https://github.com/cocur/slugify/graphs/contributors)


License
-------

The MIT License (MIT)

Copyright (c) 2012-2014 Florian Eckerstorfer

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
