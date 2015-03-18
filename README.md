cocur/slugify
=============

> Converts a string into a slug.

[![Build Status](https://img.shields.io/travis/cocur/slugify.svg?style=flat)](https://travis-ci.org/cocur/slugify)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/cocur/slugify.svg?style=flat)](https://scrutinizer-ci.com/g/cocur/slugify/)
[![Code Coverage](http://img.shields.io/coveralls/cocur/slugify.svg?style=flat)](https://coveralls.io/r/cocur/slugify)
[![Monthly Downloads](http://img.shields.io/packagist/dm/cocur/slugify.svg?style=flat)](https://packagist.org/packages/cocur/slugify)

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe with the help of 
[many great contributors](https://github.com/cocur/slugify/graphs/contributors).


Features
--------

- Removes all special characters from a string.
- Provides custom replacements for German, French, Spanish, Russian, Ukrainian, Polish, Czech, Latvian, Greek,
Esperanto¹, Arabian, Vietnamese and Burmese special characters. Instead of removing these characters, Slugify
approximates them (e.g., `ae` replaces `ä`).
- No external dependencies.
- PSR-4 compatible.
- Compatible with PHP >= 5.3.3 and [HHVM](http://hhvm.com).
- Integrations for [Symfony2](http://symfony.com), [Silex](http://silex.sensiolabs.org), [Laravel](http://laravel.com),
[Twig](http://twig.sensiolabs.org), [Zend Framework 2](http://framework.zend.com/), [Nette Framework](http://nette.org/)
and [Latte](http://latte.nette.org/).

¹ Some Esperanto transliterations conflict with others. You need to enable the Esperanto ruleset to use these transliterations.


Installation
------------

You can install Slugify through [Composer](https://getcomposer.org):

```shell
$ composer require cocur/slugify
```


Usage
-----

Generate a slug:

```php
use Cocur\Slugify\Slugify;

$slugify = new Slugify();
echo $slugify->slugify('Hello World!'); // hello-world
```

You can also change the separator used by `Slugify`:

```php
echo $slugify->slugify('Hello World!', '_'); // hello_world
```

The library also contains `Cocur\Slugify\SlugifyInterface`. Use this interface whenever you need to type hint an
instance of `Slugify`.

To add additional transliteration rules you can use the `addRule()` method.

```php
$slugify->addRule('i', 'ey');
echo $slugify->slugify('Hi'); // hey
```

### Rulesets

In addition Slugify also supports rulesets. A ruleset contains a set of rules that are not part of the default rules.
Currently one ruleset exists for Esperanto since some of the transliterations conflict with those for other languages.
The `activateRuleset()` method activates a ruleset with the given name.

```php
$slugify->activateRuleset('esperanto');
echo $slugify->slugify('serĉi manĝi'); // sercxi-mangxi
```

You can add rulesets by using `Slugify::addRuleset()` and retrieve all rulesets with `Slugify::getRuleset()`.

### Further Customization

You can also change the regular expression that is used to replace characters with the separator.

```php
$slugify = new Slugify('/([^a-z0-9]|-)+/');
// or
$slugify->setRegExp('/([^a-z0-9]|-)+/');
```

*(The regular expression used in the example above is the default one.)*

### Contributing

Feel free to ask for new rules for languages that is not already here.

All you need to do is:

1. Provide transliteration rules for your language, in any form, e.g. `'ї' => 'ji'`
2. Provide some examples of texts transliterated with this rules e.g. `'Україна' => 'Ukrajina'`

### Further information

- [API docs](http://cocur.co/slugify/api/master/)


Integrations
------------

### Symfony2

Slugify contains a Symfony2 bundle and service definition that allow you to use it as a service in your Symfony2
application. The code resides in the `Cocur\Slugify\Bridge\Symfony` namespace and you only need to add the bundle class
to your `AppKernel.php`:

```php
# app/AppKernel.php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle(),
        );
        // ...
    }

    // ...
}
```

You can now use the `cocur_slugify` service everywhere in your application, for example, in your controller:

```php
$slug = $this->get('cocur_slugify')->slugify('Hello World!');
```

The bundle also provides an alias `slugify` for the `cocur_slugify` service:

```php
$slug = $this->get('slugify')->slugify('Hello World!');
```

### Twig

If you use the Symfony2 framework with Twig you can use the Twig filter `slugify` in your templates after you have setup
Symfony2 integrations (see above).

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

To use the Twig filter with [TwigBridge](https://github.com/rcrowe/TwigBridge) for Laravel, you'll need to add the
Slugify extension using a closure:

```php
// laravel/app/config/packages/rcrowe/twigbridge/config.php

'extensions' => array(
    //...
    function () {
        return new \Cocur\Slugify\Bridge\Twig\SlugifyExtension(\Cocur\Slugify\Slugify::create());
    },
),
```

You can find more information about registering extensions in the 
[Twig documentation](http://twig.sensiolabs.org/doc/advanced.html#creating-an-extension).

### Silex

Slugify also provides a service provider to integrate into Silex.

```php
$app->register(new Cocur\Slugify\Bridge\Silex\SlugifyServiceProvider());
```

You can use the `slugify` method in your controllers:

```php
$app->get('/', function () {
    return $app['slugify']->slugify('welcome to the homepage');
});
```

And if you use Silex in combination with Twig you can also use it in your templates:

```twig
{{ app.slugify.slugify('welcome to the homepage') }}
```

Of course you can also add the Twig extension to your environment and use the `slugify` filter:

```php
$app['twig']->addExtension(new SlugifyExtension(Slugify::create()));
```

### Mustache.php

We don't need an additional integration to use Slugify in [Mustache.php](https://github.com/bobthecow/mustache.php).
If you want to use Slugify in Mustache, just add a helper:

```php
use Cocur\Slugify\Slugify;

$mustache = new Mustache_Engine(array(
    // ...
    'helpers' => array('slugify' => function($string, $separator = '-') {
        return Slugify::create()->slugify($string, $separator);
    }),
));
```

### Laravel

Slugify also provides a service provider to integrate into Laravel (versions 4.1 and later).

In your Laravel project's `app/config/app.php` file, add the service provider into the "providers" array:

```php
'providers' => array(
    "Cocur\Slugify\Bridge\Laravel\SlugifyServiceProvider",
)
```

And add the facade into the "aliases" array:

```php
'aliases' => array(
    "Slugify" => "Cocur\Slugify\Bridge\Laravel\SlugifyFacade",
)
```

You can then use the `Slugify::slugify()` method in your controllers:

```php
$url = Slugify::slugify('welcome to the homepage');
```

### Zend Framework 2

Slugify can be easely used in Zend Framework 2 applications. Included bridge provides a service and a view helper
already registered for you.

Just enable the module in your configuration like this.

```php
return array(
    //...

    'modules' => array(
        'Application',
        'ZfcBase',
        'Cocur\Slugify\Bridge\ZF2' // <- Add this line
        //...
    )

    //...
);
```

After that you can retrieve the `Cocur\Slugify\Slugify` service (or the `slugify` alias) and generate a slug.

```php
/** @var \Zend\ServiceManager\ServiceManager $sm */
$slugify = $sm->get('Cocur\Slugify\Slugify');
$slug = $slugify->slugify('Hällo Wörld');
$anotherSlug = $slugify->slugify('Hällo Wörld', '_');
```

In your view templates use the `slugify` helper to generate slugs.

```php
<?php echo $this->slugify('Hällo Wörld') ?>
<?php echo $this->slugify('Hällo Wörld', '_') ?>
```

The service (which is also used in the view helper) can be customized by defining this configuration key.

```php
return array(
    'cocur_slugify' => array(
        'reg_exp' => '/([^a-zA-Z0-9]|-)+/'
    )
);
```

### Nette Framework

Slugify contains a Nette extension that allows you to use it as a service in your Nette application. You only need to
register it in your `config.neon`:

```yml
# app/config/config.neon

extensions:
	slugify: Cocur\Slugify\Bridge\Nette\SlugifyExtension
```

You can now use the `Cocur\Slugify\SlugifyInterface` service everywhere in your application, for example in your
presenter:

```php
class MyPresenter extends \Nette\Application\UI\Presenter
{
	/** @var \Cocur\Slugify\SlugifyInterface @inject */
	public $slugify;

	public function renderDefault()
	{
		$this->template->hello = $this->slugify->slugify('Hällo Wörld');
	}
}
```

### Latte

If you use the Nette Framework with it's native Latte templating engine, you can use the Latte filter `slugify` in your
templates after you have setup Nette extension (see above).

```smarty
{$hello|slugify}
```

If you use Latte outside of the Nette Framework you first need to add the filter to your engine:

```php
use Cocur\Slugify\Bridge\Latte\SlugifyHelper;
use Cocur\Slugify\Slugify;
use Latte;

$latte = new Latte\Engine();
$latte->addFilter('slugify', array(new SlugifyHelper(Slugify::create()), 'slugify'));
```


Changelog
---------

### Version 1.1 (18 March 2015)

- [#54](https://github.com/cocur/slugify/pull/54) Add support for Burmese characters (by [lovetostrike](https://github.com/lovetostrike))
- [#58](https://github.com/cocur/slugify/pull/58) Add Nette and Latte integration (by [lookyman](https://github.com/lookyman))
- [#50](https://github.com/cocur/slugify/issues/50) Fix transliteration for Vietnamese character Đ (by [mac2000](https://github.com/mac2000))

### Version 1.0 (26 November 2014)

No new features or bugfixes, but it's about time to pump Slugify to v1.0.

### Version 0.11 (23 November 2014)

- [#49](https://github.com/cocur/slugify/pull/49) Add Zend Framework 2 integration (by [acelaya](https://github.com/acelaya))

### Version 0.10.3 (8 November 2014)

- [#48](https://github.com/cocur/slugify/pull/48) Add support for Vietnamese (by [mac2000](https://github.com/mac2000))

### Version 0.10.2 (18 October 2014)

- [#44](https://github.com/cocur/slugify/pull/44) Change visibility of properties to `protected` (by [acelaya](https://github.com/acelaya))
- [#45](https://github.com/cocur/slugify/pull/45) Configure regular expression used to replace characters (by [acelaya](https://github.com/acelaya))
- Fix type hinting (by [florianeckerstorfer](https://github.com/florianeckerstorfer))
- Remove duplicate rule (by [florianeckerstorfer](https://github.com/florianeckerstorfer))

### Version 0.10.1 (1 September 2014)

- [#39](https://github.com/cocur/slugify/pull/39) Add support for rulesets (by [florianeckerstorfer](https://github.com/florianeckerstorfer))

### Version 0.10.0 (26 August 2014)

- [#32](https://github.com/cocur/slugify/pull/32) Added Laraval bridge (by [cviebrock](https://github.com/cviebrock))
- [#35](https://github.com/cocur/slugify/pull/35) Fixed transliteration for `Ď` (by [michalskop](https://github.com/michalskop))

### Version 0.9 (29 May 2014)

- [#28](https://github.com/cocur/slugify/pull/28) Add Symfony2 service alias and make Twig extension private (by [Kevin Bond](https://github.com/kbond))

### Version 0.8 (18 April 2014)

- [#27](https://github.com/cocur/slugify/pull/27) Add support for Arabic characters (by [Davide Bellini](https://github.com/billmn))
- Added some missing characters
- Improved organisation of characters in `Slugify` class

### Version 0.7 (4 April 2014)

This version introduces optional integrations into Symfony2, Silex and Twig. You can still use the library in any other framework. I decided to include these bridges because there exist integrations from other developers, but they use outdated versions of cocur/slugify. Including these small bridge classes in the library makes maintaining them a lot easier for me.

- [#23](https://github.com/cocur/slugify/pull/23) Added Symfony2 service
- [#24](https://github.com/cocur/slugify/pull/24) Added Twig extension
- [#25](https://github.com/cocur/slugify/pull/25) Added Silex service provider

### Version 0.6 (2 April 2014)

- [#22](https://github.com/cocur/slugify/pull/22) Added support for Esperanto characters (by [Michel Petit](https://github.com/malenkiki))

### Version 0.5 (28 March 2014)

- [#21](https://github.com/cocur/slugify/pull/21) Added support for Greek characters (by [Michel Petit](https://github.com/malenkiki))
- [#20](https://github.com/cocur/slugify/pull/20) Fixed rule for cyrillic letter D (by [Marchenko Alexandr](https://github.com/cocur/slugify/pull/20))
- Add missing `$separator` parameter to `SlugifyInterface`

### Version 0.4.1 (9 March 2014)

- [#19](https://github.com/cocur/slugify/pull/19) Adds soft sign rule (by [Marchenko Alexandr](https://github.com/mac2000))

### Version 0.4 (17 January 2014)

Nearly completely rewritten code, removes `iconv` support because the underlying library is broken. The code is now better and faster. Many thanks to [Marchenko Alexandr](http://mac-blog.org.ua).

### Version 0.3 (12 January 2014)

- [#11](https://github.com/cocur/slugify/pull/11) PSR-4 compatible (by [mac2000](https://github.com/mac2000))
- [#13](https://github.com/cocur/slugify/pull/13) Added editorconfig (by [mac2000](https://github.com/mac2000))
- [#14](https://github.com/cocur/slugify/pull/14) Return empty slug when input is empty and removed unused parameter (by [mac2000](https://github.com/mac2000))


Authors
-------

- [Florian Eckerstorfer](http://florian.ec) ([Twitter](http://twitter.com/Florian_))
- [Ivo Bathke](https://github.com/ivoba)
- [Marchenko Alexandr](http://mac-blog.org.ua)
- And many [great contributors](https://github.com/cocur/slugify/graphs/contributors)


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
