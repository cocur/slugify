cocur/slugify
=============

> Converts a string into a slug.

[![Build Status](https://img.shields.io/travis/cocur/slugify.svg?style=flat)](https://travis-ci.org/cocur/slugify)
[![Windows Build status](https://ci.appveyor.com/api/projects/status/9yv498ff61byp742?svg=true)](https://ci.appveyor.com/project/florianeckerstorfer/slugify)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/cocur/slugify.svg?style=flat)](https://scrutinizer-ci.com/g/cocur/slugify/)
[![Code Coverage](https://scrutinizer-ci.com/g/cocur/slugify/badges/coverage.png?b=master&style=flat-square)](https://scrutinizer-ci.com/g/cocur/slugify/?branch=master)

[![Latest Release](https://img.shields.io/packagist/v/cocur/slugify.svg)](https://packagist.org/packages/cocur/slugify)
[![MIT License](https://img.shields.io/packagist/l/cocur/slugify.svg)](http://opensource.org/licenses/MIT)
[![Total Downloads](https://img.shields.io/packagist/dt/cocur/slugify.svg)](https://packagist.org/packages/cocur/slugify)

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe with the help of
[many great contributors](https://github.com/cocur/slugify/graphs/contributors).


Features
--------

- Removes all special characters from a string.
- Provides custom replacements for German, French, Spanish, Russian, Ukrainian, Polish, Czech, Latvian, Greek,
Esperanto¹, Arabian, Vietnamese, Burmese, Danish, Turkish, Finnish, Swedish, and Georgian special characters. Instead of
removing these characters, Slugify approximates them (e.g., `ae` replaces `ä`).
- No external dependencies.
- PSR-4 compatible.
- Compatible with PHP >= 5.5.9, PHP 7 and [HHVM](http://hhvm.com).
- Integrations for [Symfony2](http://symfony.com), [Silex](http://silex.sensiolabs.org), [Laravel](http://laravel.com),
[Twig](http://twig.sensiolabs.org), [Zend Framework 2](http://framework.zend.com/), [Nette Framework](http://nette.org/), 
[Latte](http://latte.nette.org/) and [Plum](https://github.com/plumphp/plum).

¹ Some Esperanto transliterations conflict with others. You need to enable the Esperanto ruleset to use these transliterations.


Installation
------------

You can install Slugify through [Composer](https://getcomposer.org):

```shell
$ composer require cocur/slugify
```


Usage
-----

> The documentation you can find here has already been updated for the upcoming 2.0 release. If you are using the
v1.4, the latest stable version, please use the corresponding documentation. You can find it 
[here](https://github.com/cocur/slugify/tree/1.4). 

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

Many of the transliterations rules used in Slugify are specific to a language. These rules are therefore categorized
using rulesets. Rules for the most popular are activated by default in a specific order. You can change which rulesets
are activated and the order in which they are activated. The order is important when there are conflicting rules in
different languages. For example, in German `ä` is transliterated with `ae`, in Turkish the correct transliteration is
`a`. By default the German transliteration is used since German is used more often on the internet. If you want to use
prefer the Turkish transliteration you have to possibilities. You can activate it after creating the constructor:

```php
$slugify = new Slugify();
$slugify->slugify('ä'); // -> "ae"
$slugify->activateRuleset('turkish');
$slugify->slugify('ä'); // -> "a"
```

An alternative way would be to pass the rulesets and their order to the constructor.

```php
$slugify = new Slugify(['rulesets' => ['default', 'turkish']]);
$slugify->slugify('ä'); // -> "a"
```

You can find a list of the available rulesets in `Resources/rules`.

### More options

The constructor takes an options array, you have already seen the `rulesets` options above. You can also change the 
regular expression that is used to replace characters with the separator.

```php
$slugify = new Slugify(['regexp' => '/([^A-Za-z0-9]|-)+/']);
```

*(The regular expression used in the example above is the default one.)*

By default Slugify will convert the slug to lowercase. If you want to preserve the case of the string you can set the
`lowercase` option to false.

```php
$slugify = new Slugify(['lowercase' => false]);
$slugify->slugify('Hello World'); // -> "Hello-World"
```

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

You can set the following configuration settings in `app/config.yml` to adjust the slugify service:

```yaml
cocur_slugify:
    lowercase: <boolean>
    regexp: <string>
    rulesets: { }
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

### Slim 3

Slugify does not need a specific bridge to work with [Slim 3](http://www.slimframework.com), just add the following configuration:

```php
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Cocur\Slugify\Bridge\Twig\SlugifyExtension(Cocur\Slugify\Slugify::create()));
    return $view;
};
```

In a template you can use it like this:

```twig
<a href="/blog/{{ post.title|slugify }}">{{ post.title|raw }}</a></h5>
```


Change Log
----------

### Version 2.1.1 (8 April 2016)

- Do not activate Swedish rules by default (fixes broken v2.1 release)

### Version 2.1.0 (8 April 2016)

- [#104](https://github.com/cocur/slugify/pull/104) Add Symfony configuration (by [estahn](https://github.com/estahn))
- [#107](https://github.com/cocur/slugify/issues/107) Fix Swedish rules

### Version 2.0.0 (24 February 2016)

- [#78](https://github.com/cocur/slugify/pull/78) Use multibyte-safe case convention (by [Koc](https://github.com/Koc))
- [#81](https://github.com/cocur/slugify/pull/81) Move rules into JSON files (by [florianeckerstorfer](https://github.com/florianeckerstorfer))
- [#84](https://github.com/cocur/slugify/pull/84) Add tests for very long strings containing umlauts (by [florianeckerstorfer](https://github.com/florianeckerstorfer))
- [#88](https://github.com/cocur/slugify/pull/88) Add rules for Hindi (by [florianeckerstorfer](https://github.com/florianeckerstorfer))
- [#89](https://github.com/cocur/slugify/pull/89) Add rules for Norwegian (by [tsmes](https://github.com/tsmes))
- [#90](https://github.com/cocur/slugify/pull/90) Replace `bindShared` with `singleton` in Laravel bridge (by [sunspikes](https://github.com/sunspikes))
- [#97](https://github.com/cocur/slugify/pull/97) Set minimum PHP version to 5.5.9 (by [florianeckerstorfer](https://github.com/florianeckerstorfer))
- [#98](https://github.com/cocur/slugify/pull/98) Add rules for Bulgarian (by [RoumenDamianoff](https://github.com/RoumenDamianoff))


### Version 1.4.1 (11 February 2016)

- [#90](https://github.com/cocur/slugify/pull/90) Replace `bindShared` with `singleton` in Laravel bridge (by [sunspikes](https://github.com/sunspikes))

### Version 1.4 (29 September 2015)

- [#75](https://github.com/cocur/slugify/pull/75) Remove a duplicate array entry (by [irfanevrens](https://github.com/irfanevrens))
- [#76](https://github.com/cocur/slugify/pull/76) Add support for Georgian (by [TheGIBSON](https://github.com/TheGIBSON))
- [#77](https://github.com/cocur/slugify/pull/77) Fix Danish transliterations (by [kafoso](https://github.com/kafoso))

### Version 1.3 (2 September 2015)

- [#70](https://github.com/cocur/slugify/pull/70) Add missing superscript and subscript digits (by [BlueM](https://github.com/BlueM))
- [#71](https://github.com/cocur/slugify/pull/71) Improve Greek language support (by [kostaspt](https://github.com/kostaspt))
- [#72](https://github.com/cocur/slugify/pull/72) Improve Silex integration (by [CarsonF](https://github.com/CarsonF))
- [#73](https://github.com/cocur/slugify/pull/73) Improve Russian language support (by [akost](https://github.com/akost))

### Version 1.2 (2 July 2015)

- Add integration for [Plum](https://github.com/plumphp/plum) (by [florianeckerstorfer](https://github.com/florianeckerstorfer))
- [#64](https://github.com/cocur/slugify/pull/64) Fix Nette integration (by [lookyman](https://github.com/lookyman))
- Add option to not convert slug to lowercase (by [florianeckerstorfer](https://github.com/florianeckerstorfer) and [GDmac](https://github.com/GDmac))

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

> Slugify is a project of [Cocur](http://cocur.co). You can contact us on Twitter:
> [**@cocurco**](https://twitter.com/cocurco)


Support
-------

If you need support you can ask on [Twitter](https://twitter.com/cocurco) (well, only if your question is short) or you
can join our chat on Gitter.

[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/cocur/slugify)

In case you want to support the development of Slugify you can help us with providing additional transliterations or
inform us if a transliteration is wrong. We would highly appreciate it if you can send us directly a Pull Request on
Github. If you have never contributed to a project on Github we are happy to help you. Just ask on Twitter or directly
join our Gitter.

You always can help me (Florian, the original developer and maintainer) out by 
[sending me an Euro or two](https://paypal.me/florianec/2).


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
