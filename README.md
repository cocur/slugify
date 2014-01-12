Cocur Slugify
=============

Converts a string into a slug.

Authors
-------

- [Florian Eckerstorfer](http://florian.ec) ([Twitter](http://twitter.com/Florian_), [App.net](http://app.net/florian))
- Ivo Bathke
- And some [great contributors](https://github.com/cocur/slugify/graphs/contributors)


Features
--------

Slugify has two mechanism to slug a string:

- Using TRANSLIT from iconv
- Using an array map to translit utf-8 chars to their 7bit representation

The two mechanism are taken and modified from the [Doctrine](http://www.doctrine-project.org) project and
[Laravel](http://laravel.com) framework.


Usage
-----

Generate a slug using the *iconv* extension:

	<?php
	use Cocur\Slugify\Slugify;

	$slugify = new Slugify(); // for iconv translit
	echo $slugify->slugify('Hello World!'); // hello-world

Or generate a slug without using the *iconv* extension:

    <?php
    use Cocur\Slugify\Slugify;

    $slugify = new Slugify(Slugify::MODEARRAY);
    echo $slugify->slugify('Hello World!'); // hello-world


Changelog
---------

### Version 0.3 (12 January 2014)

- #11 PSR-4 compatible (by [mac2000](https://github.com/mac2000))
- #12 Return empty slug when input is empty and removed unused parameter (by [mac2000](https://github.com/mac2000))
- #13 Added editorconfig (by [mac2000](https://github.com/mac2000))


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


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/cocur/slugify/trend.png)](https://bitdeli.com/free "Bitdeli Badge")
