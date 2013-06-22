Cocur Slugify
=============

Converts a string into a slug.

Author
------

Started in Vienna by [Florian Eckerstorfer](http://florianeckerstorfer.com) ([@Florian_](http://twitter.com/Florian_)).

Improved by Ivo Bathke  

Slugify has two mechanism to slug a string:  
as default it works with TRANSLIT from iconv, but it also has a static array to translit utf-8 chars to their 7bit representation.

The two mechanism are taken and modified from doctrine project and laravel framework. 

Usage
-----

	<?php
	use Cocur\Slugify\Slugify;

	$slugify = new Slugify();//for iconv translit
    //$slugify = new Slugify(Slugify::MODEARRAY);//for array map
	echo $slugify->slugify('Hello World!'); // hello-world
	?>

License
-------

The MIT License (MIT)

Copyright (c) 2012 Florian Eckerstorfer

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
