cocur/slugify
=============

> Converts a string into a slug.

[![Build Status](https://travis-ci.org/cocur/slugify.png?branch=master)](https://travis-ci.org/cocur/slugify)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/cocur/slugify/badges/quality-score.png?s=6dc4ff1137d4405f75be9e98c74b1b70fcfdffaa)](https://scrutinizer-ci.com/g/cocur/slugify/)
[![Code Coverage](https://scrutinizer-ci.com/g/cocur/slugify/badges/coverage.png?s=27306b142814efca5c7a99984d01a073e453309f)](https://scrutinizer-ci.com/g/cocur/slugify/)
[![Latest Stable Version](https://poser.pugx.org/cocur/slugify/v/stable.png)](https://packagist.org/packages/cocur/slugify)
[![Total Downloads](https://poser.pugx.org/cocur/slugify/downloads.png)](https://packagist.org/packages/cocur/slugify)


Features
--------

- Removes all special characters from a string.
- Provides custom replacements for German, French, Spanish, Cyrillic, Greek and Esperanto special characters. Instead of removing these characters Slugify approximates them (e.g., `ae` replaces `ä`).
- No external dependencies.
- PSR-4 compatible.
- Compatible with PHP >= 5.3.3 and [HHVM](http://hhvm.com).
- Integrations for Symfony2 and Twig.


Installation
------------

You can install cocur/slugify through [Composer](https://getcomposer.org):

```shell
$ composer require cocur/slugify:@stable
```

*In a production environment you should replace `@stable` with the [version](https://github.com/cocur/slugify/releases) you want to use.*


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


Integrations
------------

### Symfony2

Slugify contains a Symfony2 bundle and service definition that allow you to use it as a service in your Symfony2 application. The code resides in the `Cocur\Slugify\Bridge\Bundle` namespace and you only need to add the bundle class to your `AppKernel.php`:

```php
# app/AppKernel.php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Cocur\Slugify\Bridge\Bundle\CocurSlugifyBundle(),
        );
        // ...
    }

    // ...
}
```

### Twig

If you use the Symfony2 framework with Twig you can use the Twig filter `slugify` in your templates after you have setup Symfony2 integrations (see above).

```twig
{{ 'Hällo Wörld'|slugify }}
```

If you use Twig outside of the Symfony2 framework you first need to add the extension to your environment:

```php
use Cocur\Slugify\Bridge\Twig\SlugifyExtension;
use Cocur\Slugify\Slugify;

$twig = new Twig_Environment($loader);
$twig->addExtension(new SlugifyExtension(Slugify::create()));
```

You can find more information about registering extensions in the [Twig documentation](http://twig.sensiolabs.org/doc/advanced.html#creating-an-extension).


Changelog
---------

### Version 0.7 (4 April 2014)

This version introduces optional integrations into Symfony2, Silex and Twig. You can still use the library in any other framework. I decided to include these bridges because there exist integrations from other developers, but they use outdated versions of cocur/slugify. Including these small bridge classes in the library makes maintaining them a lot easier for me.

- #23 Added Symfony2 service
- #24 Added Twig extension
- #25 Added Silex service provider

### Version 0.6 (2 April 2014)

- #22 Added support for Esperanto characters (by [Michel Petit](https://github.com/malenkiki))

### Version 0.5 (28 March 2014)

- #21 Added support for Greek characters (by [Michel Petit](https://github.com/malenkiki))
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
