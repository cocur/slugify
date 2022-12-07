# cocur/slugify

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

## Features

-   Removes all special characters from a string.
-   Provides custom replacements for Arabic, Austrian, Azerbaijani, Brazilian Portuguese, Bulgarian, Burmese, Chinese, Croatian, Czech, Esperanto, Estonian, Finnish, French, Georgian, German, Greek, Hindi, Hungarian, Italian, Latvian, Lithuanian, Macedonian, Norwegian, Polish, Romanian, Russian, Serbian, Spanish, Swedish, Turkish, Ukrainian and Vietnamese special characters. Instead of removing these characters, Slugify approximates them (e.g., `ae` replaces `ä`).
-   No external dependencies.
-   PSR-4 compatible.
-   Compatible with PHP >= 7.
-   Integrations for [Symfony (3, 4 and 5)](http://symfony.com), [Laravel](http://laravel.com), [Twig (2 and 3)](http://twig.sensiolabs.org), [Zend Framework 2](http://framework.zend.com/), [Nette Framework](http://nette.org/), [Latte](http://latte.nette.org/) and [Plum](https://github.com/plumphp/plum).

## Installation

You can install Slugify through [Composer](https://getcomposer.org):

```shell
composer require cocur/slugify
```

Slugify requires the Multibyte String extension from PHP. Typically you can use the configure option `--enable-mbstring` while compiling PHP. More information can be found in the [PHP documentation](http://php.net/manual/en/mbstring.installation.php).

Further steps may be needed for [integrations](#integrations).

## Usage

Generate a slug:

```php
use Cocur\Slugify\Slugify;

$slugify = new Slugify();
echo $slugify->slugify("Hello World!"); // hello-world
```

You can also change the separator used by `Slugify`:

```php
echo $slugify->slugify("Hello World!", "_"); // hello_world
```

The library also contains `Cocur\Slugify\SlugifyInterface`. Use this interface whenever you need to type hint an
instance of `Slugify`.

To add additional transliteration rules you can use the `addRule()` method.

```php
$slugify->addRule("i", "ey");
echo $slugify->slugify("Hi"); // hey
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
$slugify->slugify("ä"); // -> "ae"
$slugify->activateRuleSet("turkish");
$slugify->slugify("ä"); // -> "a"
```

An alternative way would be to pass the rulesets and their order to the constructor.

```php
$slugify = new Slugify(["rulesets" => ["default", "turkish"]]);
$slugify->slugify("ä"); // -> "a"
```

You can find a list of the available rulesets in [Resources/rules](https://github.com/cocur/slugify/tree/master/Resources/rules).

### More options

The constructor takes an options array, you have already seen the `rulesets` options above. You can also change the
regular expression that is used to replace characters with the separator.

```php
$slugify = new Slugify(["regexp" => "/([^A-Za-z0-9]|-)+/"]);
```

_(The regular expression used in the example above is the default one.)_

By default Slugify will convert the slug to lowercase. If you want to preserve the case of the string you can set the
`lowercase` option to false.

```php
$slugify = new Slugify(["lowercase" => false]);
$slugify->slugify("Hello World"); // -> "Hello-World"
```

Lowercasing is done before using the regular expression. If you want to keep the lowercasing behavior but your regular
expression needs to match uppercase letters, you can set the `lowercase_after_regexp` option to `true`.

```php
$slugify = new Slugify([
    "regexp" => "/(?<=[[:^upper:]])(?=[[:upper:]])/",
    "lowercase_after_regexp" => false,
]);
$slugify->slugify("FooBar"); // -> "foo-bar"
```

By default Slugify will use dashes as separators. If you want to use a different default separator, you can set the
`separator` option.

```php
$slugify = new Slugify(["separator" => "_"]);
$slugify->slugify("Hello World"); // -> "hello_world"
```

By default Slugify will remove leading and trailing separators before returning the slug. If you do not want the slug to
be trimmed you can set the `trim` option to false.

```php
$slugify = new Slugify(["trim" => false]);
$slugify->slugify("Hello World "); // -> "hello-world-"
```

### Changing options on the fly

You can overwrite any of the above options on the fly by passing an options array as second argument to the `slugify()`
method. For example:

```php
$slugify = new Slugify();
$slugify->slugify("Hello World", ["lowercase" => false]); // -> "Hello-World"
```

You can also modify the separator this way:

```php
$slugify = new Slugify();
$slugify->slugify("Hello World", ["separator" => "_"]); // -> "hello_world"
```

You can even activate a custom ruleset without touching the default rules:

```php
$slugify = new Slugify();
$slugify->slugify("für", ["ruleset" => "turkish"]); // -> "fur"
$slugify->slugify("für"); // -> "fuer"
```

### Contributing

We really appreciate if you report bugs and errors in the transliteration, especially if you are a native speaker of
the language and question. Feel free to ask for additional languages in the issues, but please note that the
maintainer of this repository does not speak all languages. If you can provide a Pull Request with rules for
a new language or extend the rules for an existing language that would be amazing.

To add a new language you need to:

1. Create a `[language].json` in `Resources/rules`
2. If you believe the language should be a default ruleset you can add the language to
   `Cocur\Slugify\Slugify::$options`. If you add the language there all existing tests still have to pass
3. Run `php bin/generate-default.php`
4. Add tests for the language in `tests/SlugifyTest.php`. If the language is in the default ruleset add your
   test cases to `defaultRuleProvider()`, otherwise to `customRulesProvider()`.

Submit PR. Thank you very much. 💚

### Code of Conduct

In the interest of fostering an open and welcoming environment, we as contributors and maintainers pledge to making participation in our project and our community a harassment-free experience for everyone, regardless of age, body size, disability, ethnicity, gender identity and expression, level of experience, nationality, personal appearance, race, religion, or sexual identity and orientation.

The full Code of Conduct can be found [here](https://github.com/cocur/slugify/blob/master/CODE_OF_CONDUCT.md).

This project is no place for hate. If you have any problems please contact Florian: [florian@eckerstorfer.net](mailto:florian@eckerstorfer.net) ✌🏻🏳️‍🌈

### Further information

-   [API docs](http://cocur.co/slugify/api/master/)

## Integrations

### Symfony

Slugify contains a Symfony bundle and service definition that allow you to use it as a service in your Symfony application. The code resides in `Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle` and you only need to activate it:

#### Symfony 2

Support for Symfony 2 has been dropped in Slugify 4.0.0, use `cocur/slugify@3`.

#### Symfony 3

```php
// app/AppKernel.php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle(),
        ];
    }
}
```

#### Symfony >= 4

```php
// config/bundles.php

return [
    // ...
    Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle::class => ["all" => true],
];
```

You can now use the `cocur_slugify` service everywhere in your application, for example, in your controller:

```php
$slug = $this->get("cocur_slugify")->slugify("Hello World!");
```

The bundle also provides an alias `slugify` for the `cocur_slugify` service:

```php
$slug = $this->get("slugify")->slugify("Hello World!");
```

If you use `autowire` (Symfony >=3.3), you can inject it into your services like this:

```php
public function __construct(\Cocur\Slugify\SlugifyInterface $slugify)
```

#### Symfony Configuration

You can set the following configuration settings in `config.yml` (Symfony 2-3) or
`config/packages/cocur_slugify.yaml` (Symfony 4) to adjust the slugify service:

```yaml
cocur_slugify:
    lowercase: false # or true
    separator: "-" # any string
    # regexp: <string>
    rulesets: ["austrian"] # List of rulesets: https://github.com/cocur/slugify/tree/master/Resources/rules
```

### Twig

If you use the Symfony framework with Twig you can use the Twig filter `slugify` in your templates after you have setup
Symfony integrations (see above).

```twig
{{ 'Hällo Wörld'|slugify }}
```

If you use Twig outside of the Symfony framework you first need to add the extension to your environment:

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

### Mustache.php

We don't need an additional integration to use Slugify in [Mustache.php](https://github.com/bobthecow/mustache.php).
If you want to use Slugify in Mustache, just add a helper:

```php
use Cocur\Slugify\Slugify;

$mustache = new Mustache_Engine([
    // ...
    "helpers" => [
        "slugify" => function ($string, $separator = null) {
            return Slugify::create()->slugify($string, $separator);
        },
    ],
]);
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
$url = Slugify::slugify("welcome to the homepage");
```

### Zend Framework 2

Slugify can be easely used in Zend Framework 2 applications. Included bridge provides a service and a view helper
already registered for you.

Just enable the module in your configuration like this.

```php
return [
    //...

    "modules" => [
        "Application",
        "ZfcBase",
        "Cocur\Slugify\Bridge\ZF2", // <- Add this line
        //...
    ],

    //...
];
```

After that you can retrieve the `Cocur\Slugify\Slugify` service (or the `slugify` alias) and generate a slug.

```php
/** @var \Zend\ServiceManager\ServiceManager $sm */
$slugify = $sm->get("Cocur\Slugify\Slugify");
$slug = $slugify->slugify("Hällo Wörld");
$anotherSlug = $slugify->slugify("Hällo Wörld", "_");
```

In your view templates use the `slugify` helper to generate slugs.

```php
<?php echo $this->slugify("Hällo Wörld"); ?>
<?php echo $this->slugify("Hällo Wörld", "_"); ?>
```

The service (which is also used in the view helper) can be customized by defining this configuration key.

```php
return [
    "cocur_slugify" => [
        "reg_exp" => "/([^a-zA-Z0-9]|-)+/",
    ],
];
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
        $this->template->hello = $this->slugify->slugify("Hällo Wörld");
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
$latte->addFilter("slugify", [new SlugifyHelper(Slugify::create()), "slugify"]);
```

### Slim 3

Slugify does not need a specific bridge to work with [Slim 3](http://www.slimframework.com), just add the following configuration:

```php
$container["view"] = function ($c) {
    $settings = $c->get("settings");
    $view = new \Slim\Views\Twig(
        $settings["view"]["template_path"],
        $settings["view"]["twig"]
    );
    $view->addExtension(
        new Slim\Views\TwigExtension(
            $c->get("router"),
            $c->get("request")->getUri()
        )
    );
    $view->addExtension(
        new Cocur\Slugify\Bridge\Twig\SlugifyExtension(
            Cocur\Slugify\Slugify::create()
        )
    );
    return $view;
};
```

In a template you can use it like this:

```twig
<a href="/blog/{{ post.title|slugify }}">{{ post.title|raw }}</a></h5>
```

### League

Slugify provides a service provider for use with `league/container`:

```php
use Cocur\Slugify;
use League\Container;

/* @var Container\ContainerInterface $container */
$container->addServiceProvider(
    new Slugify\Bridge\League\SlugifyServiceProvider()
);

/* @var Slugify\Slugify $slugify */
$slugify = $container->get(Slugify\SlugifyInterface::class);
```

You can configure it by sharing the required options:

```php
use Cocur\Slugify;
use League\Container;

/* @var Container\ContainerInterface $container */
$container->share("config.slugify.options", [
    "lowercase" => false,
    "rulesets" => ["default", "german"],
]);

$container->addServiceProvider(
    new Slugify\Bridge\League\SlugifyServiceProvider()
);

/* @var Slugify\Slugify $slugify */
$slugify = $container->get(Slugify\SlugifyInterface::class);
```

You can configure which rule provider to use by sharing it:

```php
use Cocur\Slugify;
use League\Container;

/* @var Container\ContainerInterface $container */
$container->share(Slugify\RuleProvider\RuleProviderInterface::class, function () {
    return new Slugify\RuleProvider\FileRuleProvider(__DIR__ . '/../../rules');
]);

$container->addServiceProvider(new Slugify\Bridge\League\SlugifyServiceProvider());

/* @var Slugify\Slugify $slugify */
$slugify = $container->get(Slugify\SlugifyInterface::class);
```

## Change Log

### Version 4.3 (7 December 2022)

-   [#317](https://github.com/cocur/slugify/pull/317) Add PHP 8.2 support (by [fezfez](https://github.com/fezfez))

### Version 4.2 (13 August 2022)

-   [#305](https://github.com/cocur/slugify/pull/305) Add support for custom fonts (by [luca-alsina](https://github.com/luca-alsina))
-   [#309](https://github.com/cocur/slugify/pull/309) Add handling for undefined rulesets (by [aadmathijssen](https://github.com/aadmathijssen))
-   [#227](https://github.com/cocur/slugify/pull/227) Add support for capital sharp s (by [weeman1337](https://github.com/weeman1337))
-   [#312](https://github.com/cocur/slugify/pull/312) Fix composer.lock file (by [florianeckerstorfer](https://github.com/florianeckerstorfer))
-   [#313](https://github.com/cocur/slugify/pull/313) Update PHP version requirement (by [florianeckerstorfer](https://github.com/florianeckerstorfer))

### Version 4.1 (11 January 2022)

Support for Symfony 6.

-   [#244](https://github.com/cocur/slugify/pull/244) .gitignore cleanup (by [kubawerlos](https://github.com/kubawerlos))
-   [#259](https://github.com/cocur/slugify/pull/259) Fix portuguese-brazil language (by [stephandesouza](https://github.com/stephandesouza))
-   [#272](https://github.com/cocur/slugify/pull/272) Improve tests about assertions (by [peter279k](https://github.com/peter279k))
-   [#278](https://github.com/cocur/slugify/pull/278) Update georgian.json (by [nikameto](https://github.com/nikameto))
-   [#299](https://github.com/cocur/slugify/pull/299) Allow Symfony 6 and resolve depreciations (by [GromNaN](https://github.com/GromNaN))
-   [#264](https://github.com/cocur/slugify/pull/264) Add new Gujarati language (by [infynnoTech](https://github.com/infynnoTech))
-   [#297](https://github.com/cocur/slugify/pull/297) More Yoruba character support (by [9jaGuy](https://github.com/9jaGuy))

### Version 4.0 (14 December 2019)

Version 4 does not introduce new major features, but adds support for Symfony 4 and 5, Twig 3 and, most importantly, PHP 7.3 and 7.4.

Support for PHP 5, Twig 1 and Silex is dropped.

-   [#230](https://github.com/cocur/slugify/pull/230) Add Slovak rules (by [bartko-s](https://github.com/bartko-s))
-   [#236](https://github.com/cocur/slugify/pull/236) Make Twig Bridge compatible with Twig 3.0 (by [mhujer](https://github.com/mhujer))
-   [#237](https://github.com/cocur/slugify/pull/237) Fix Travis CI configuration (by [kubawerlos](https://github.com/kubawerlos))
-   [#238](https://github.com/cocur/slugify/pull/238) Drop Twig 1 support (by [FabienPapet](https://github.com/FabienPapet))
-   [#239](https://github.com/cocur/slugify/pull/239) Fix AppVeyor (by [kubawerlos](https://github.com/kubawerlos))
-   [#241](https://github.com/cocur/slugify/pull/241) Update .gitattributes (by [kubawerlos](https://github.com/kubawerlos))
-   [#242](https://github.com/cocur/slugify/pull/242) Add PHP CS Fixer (by [kubawerlos](https://github.com/kubawerlos))
-   [#243](https://github.com/cocur/slugify/pull/243) Normalize composer.json (by [kubawerlos](https://github.com/kubawerlos))
-   [#246](https://github.com/cocur/slugify/pull/246) Add support for PHP 7.3 and 7.4 (by [snapshotpl](https://github.com/snapshotpl))
-   [#247](https://github.com/cocur/slugify/pull/247) AppVeyor improvements (by [kubawerlos](https://github.com/kubawerlos))
-   [#249](https://github.com/cocur/slugify/pull/249) PHPUnit annotations should be a FQCNs including a root namespace (by [kubawerlos](https://github.com/kubawerlos))
-   [#250](https://github.com/cocur/slugify/pull/250) Add support for Symfony 4 and 5 (by [franmomu](https://github.com/franmomu))
-   [#251](https://github.com/cocur/slugify/pull/251) Dropping support for PHP 5 (by [franmomu](https://github.com/franmomu))
-   [#253](https://github.com/cocur/slugify/pull/253) Add conflict for unmaintained Symfony versions (by [franmomu](https://github.com/franmomu))

### Version 3.2 (31 January 2019)

-   [#201](https://github.com/cocur/slugify/pull/201) Add strip_tags option (by [thewilkybarkid](https://github.com/thewilkybarkid))
-   [#212](https://github.com/cocur/slugify/pull/212) Fix Macedonian Dze (by [franmomu](https://github.com/franmomu))
-   [#213](https://github.com/cocur/slugify/pull/213) Add support for Turkmen (by [umbarov](https://github.com/umbarov))
-   [#216](https://github.com/cocur/slugify/pull/216) Add lowercase_after_regexp option (by [julienfalque](https://github.com/julienfalque))
-   [#217](https://github.com/cocur/slugify/pull/217) Simplify default regular impression (by [julienfalque](https://github.com/julienfalque))
-   [#220](https://github.com/cocur/slugify/pull/220) Fix deprecation warning for symfony/config 4.2+ (by [franmomu](https://github.com/franmomu))
-   [#221](https://github.com/cocur/slugify/pull/221) Add suuport Armenian (by [boolfalse](https://github.com/boolfalse))

### Version 3.1 (22 January 2018)

-   [#195](https://github.com/cocur/slugify/pull/195) Add support for Chinese (Pinyin) (by [SuN-80](https://github.com/SuN-80), [franmomu](https://github.com/franmomu))
-   [#189](https://github.com/cocur/slugify/pull/189) Add trim option (by [sforsberg](https://github.com/sforsberg))

### Version 3.0.1 (24 September 2017)

-   [#183](https://github.com/cocur/slugify/pull/183) Fix invalid JSON ([RusiPapazov](https://github.com/RusiPapazov))
-   [#185](https://github.com/cocur/slugify/pull/185) Fix support for Symfony > 3.3 (by [FabienPapet](https://github.com/FabienPapet))
-   [#186](https://github.com/cocur/slugify/pull/186) Require Multibyte extension in `composer.json` (by [wandersonwhcr](https://github.com/wandersonwhcr))

### Version 3.0 (11 August 2017)

-   HHVM is no longer supported
-   Bugfix [#165](https://github.com/cocur/slugify/issues/165) Added missing French rules to `DefaultRuleProvider` (by [gsouf](https://github.com/gsouf))
-   [#168](https://github.com/cocur/slugify/pull/168) Add Persian rules (by [mohammad6006](https://github.com/mohammad6006))
-   Bugfix [#169](https://github.com/cocur/slugify/issues/169) Add missing `getName()` to `Cocur\Slugify\Bridge\Twig\SlugifyExtension` (by [TomCan](https://github.com/TomCan))
-   [#172](https://github.com/cocur/slugify/pull/172) Sort rules in `DefaultRuleProvider` alphabetically (by [tbmatuka](https://github.com/tbmatuka))
-   [#174](https://github.com/cocur/slugify/pull/174) Add Hungarian rules (by [rviktor87](https://github.com/rviktor87))
-   [#180](https://github.com/cocur/slugify/pull/180) Add Brazilian Portuguese rules (by [tallesairan](https://github.com/tallesairan))
-   Bugfix [#181](https://github.com/cocur/slugify/pull/181) Add missing French rules (by [FabienPapet](https://github.com/FabienPapet))

### Version 2.5 (23 March 2017)

-   [#150](https://github.com/cocur/slugify/pull/150) Add Romanian rules (by [gabiudrescu](https://github.com/gabiudrescu))
-   [#154](https://github.com/cocur/slugify/pull/154) Add French rules (by [SuN-80](https://github.com/SuN-80))
-   [#159](https://github.com/cocur/slugify/pull/159) Add Estonian rules (by [erkimiilberg](https://github.com/erkimiilberg))
-   [#162](https://github.com/cocur/slugify/pull/162) Add support for Twig 2 (by [JakeFr](https://github.com/JakeFr))

### Version 2.4 (9 February 2017)

-   [#133](https://github.com/cocur/slugify/pull/133) Allow to modify options without creating a new object (by [leofeyer](https://github.com/leofeyer))
-   [#135](https://github.com/cocur/slugify/pull/135) Add support for Danish (by [izehose](https://github.com/izehose))
-   [#140](https://github.com/cocur/slugify/pull/140) Update Hindi support (by [arunlodhi](https://github.com/arunlodhi))
-   [#146](https://github.com/cocur/slugify/pull/146) Add support for Italien (by [gianiaz](https://github.com/gianiaz))
-   [#151](https://github.com/cocur/slugify/pull/151) Add support for Serbian (by [cvetan](https://github.com/cvetan))
-   [#155](https://github.com/cocur/slugify/pull/155) Update support for Lithuanian (by [s4uliu5](https://github.com/s4uliu5))

### Version 2.3 (9 August 2016)

-   [#124](https://github.com/cocur/slugify/issues/124) Fix support for Bulgarian
-   [#125](https://github.com/cocur/slugify/pull/125) Update Silex 2 provider (by [JakeFr](https://github.com/JakeFr))
-   [#129](https://github.com/cocur/slugify/pull/129) Add support for Croatian (by [napravicukod](https://github.com/napravicukod))

### Version 2.2 (10 July 2016)

-   [#102](https://github.com/cocur/slugify/pull/102) Add transliterations for Azerbaijani (by [seferov](https://github.com/seferov))
-   [#109](https://github.com/cocur/slugify/pull/109) Made integer values into strings (by [JonathanMH](https://github.com/JonathanMH))
-   [#114](https://github.com/cocur/slugify/pull/114) Provide SlugifyServiceProvider for league/container (by [localheinz](https://github.com/localheinz))
-   [#120](https://github.com/cocur/slugify/issues/120) Add compatibility with Silex 2 (by [shamotj](https://github.com/shamotj))

### Version 2.1.1 (8 April 2016)

-   Do not activate Swedish rules by default (fixes broken v2.1 release)

### Version 2.1.0 (8 April 2016)

-   [#104](https://github.com/cocur/slugify/pull/104) Add Symfony configuration (by [estahn](https://github.com/estahn))
-   [#107](https://github.com/cocur/slugify/issues/107) Fix Swedish rules

### Version 2.0.0 (24 February 2016)

-   [#78](https://github.com/cocur/slugify/pull/78) Use multibyte-safe case convention (by [Koc](https://github.com/Koc))
-   [#81](https://github.com/cocur/slugify/pull/81) Move rules into JSON files (by [florianeckerstorfer](https://github.com/florianeckerstorfer))
-   [#84](https://github.com/cocur/slugify/pull/84) Add tests for very long strings containing umlauts (by [florianeckerstorfer](https://github.com/florianeckerstorfer))
-   [#88](https://github.com/cocur/slugify/pull/88) Add rules for Hindi (by [florianeckerstorfer](https://github.com/florianeckerstorfer))
-   [#89](https://github.com/cocur/slugify/pull/89) Add rules for Norwegian (by [tsmes](https://github.com/tsmes))
-   [#90](https://github.com/cocur/slugify/pull/90) Replace `bindShared` with `singleton` in Laravel bridge (by [sunspikes](https://github.com/sunspikes))
-   [#97](https://github.com/cocur/slugify/pull/97) Set minimum PHP version to 5.5.9 (by [florianeckerstorfer](https://github.com/florianeckerstorfer))
-   [#98](https://github.com/cocur/slugify/pull/98) Add rules for Bulgarian (by [RoumenDamianoff](https://github.com/RoumenDamianoff))

### Version 1.4.1 (11 February 2016)

-   [#90](https://github.com/cocur/slugify/pull/90) Replace `bindShared` with `singleton` in Laravel bridge (by [sunspikes](https://github.com/sunspikes))

### Version 1.4 (29 September 2015)

-   [#75](https://github.com/cocur/slugify/pull/75) Remove a duplicate array entry (by [irfanevrens](https://github.com/irfanevrens))
-   [#76](https://github.com/cocur/slugify/pull/76) Add support for Georgian (by [TheGIBSON](https://github.com/TheGIBSON))
-   [#77](https://github.com/cocur/slugify/pull/77) Fix Danish transliterations (by [kafoso](https://github.com/kafoso))

### Version 1.3 (2 September 2015)

-   [#70](https://github.com/cocur/slugify/pull/70) Add missing superscript and subscript digits (by [BlueM](https://github.com/BlueM))
-   [#71](https://github.com/cocur/slugify/pull/71) Improve Greek language support (by [kostaspt](https://github.com/kostaspt))
-   [#72](https://github.com/cocur/slugify/pull/72) Improve Silex integration (by [CarsonF](https://github.com/CarsonF))
-   [#73](https://github.com/cocur/slugify/pull/73) Improve Russian language support (by [akost](https://github.com/akost))

### Version 1.2 (2 July 2015)

-   Add integration for [Plum](https://github.com/plumphp/plum) (by [florianeckerstorfer](https://github.com/florianeckerstorfer))
-   [#64](https://github.com/cocur/slugify/pull/64) Fix Nette integration (by [lookyman](https://github.com/lookyman))
-   Add option to not convert slug to lowercase (by [florianeckerstorfer](https://github.com/florianeckerstorfer) and [GDmac](https://github.com/GDmac))

### Version 1.1 (18 March 2015)

-   [#54](https://github.com/cocur/slugify/pull/54) Add support for Burmese characters (by [lovetostrike](https://github.com/lovetostrike))
-   [#58](https://github.com/cocur/slugify/pull/58) Add Nette and Latte integration (by [lookyman](https://github.com/lookyman))
-   [#50](https://github.com/cocur/slugify/issues/50) Fix transliteration for Vietnamese character Đ (by [mac2000](https://github.com/mac2000))

### Version 1.0 (26 November 2014)

No new features or bugfixes, but it's about time to pump Slugify to v1.0.

### Version 0.11 (23 November 2014)

-   [#49](https://github.com/cocur/slugify/pull/49) Add Zend Framework 2 integration (by [acelaya](https://github.com/acelaya))

### Version 0.10.3 (8 November 2014)

-   [#48](https://github.com/cocur/slugify/pull/48) Add support for Vietnamese (by [mac2000](https://github.com/mac2000))

### Version 0.10.2 (18 October 2014)

-   [#44](https://github.com/cocur/slugify/pull/44) Change visibility of properties to `protected` (by [acelaya](https://github.com/acelaya))
-   [#45](https://github.com/cocur/slugify/pull/45) Configure regular expression used to replace characters (by [acelaya](https://github.com/acelaya))
-   Fix type hinting (by [florianeckerstorfer](https://github.com/florianeckerstorfer))
-   Remove duplicate rule (by [florianeckerstorfer](https://github.com/florianeckerstorfer))

### Version 0.10.1 (1 September 2014)

-   [#39](https://github.com/cocur/slugify/pull/39) Add support for rulesets (by [florianeckerstorfer](https://github.com/florianeckerstorfer))

### Version 0.10.0 (26 August 2014)

-   [#32](https://github.com/cocur/slugify/pull/32) Added Laraval bridge (by [cviebrock](https://github.com/cviebrock))
-   [#35](https://github.com/cocur/slugify/pull/35) Fixed transliteration for `Ď` (by [michalskop](https://github.com/michalskop))

### Version 0.9 (29 May 2014)

-   [#28](https://github.com/cocur/slugify/pull/28) Add Symfony2 service alias and make Twig extension private (by [Kevin Bond](https://github.com/kbond))

### Version 0.8 (18 April 2014)

-   [#27](https://github.com/cocur/slugify/pull/27) Add support for Arabic characters (by [Davide Bellini](https://github.com/billmn))
-   Added some missing characters
-   Improved organisation of characters in `Slugify` class

### Version 0.7 (4 April 2014)

This version introduces optional integrations into Symfony2, Silex and Twig. You can still use the library in any other framework. I decided to include these bridges because there exist integrations from other developers, but they use outdated versions of cocur/slugify. Including these small bridge classes in the library makes maintaining them a lot easier for me.

-   [#23](https://github.com/cocur/slugify/pull/23) Added Symfony2 service
-   [#24](https://github.com/cocur/slugify/pull/24) Added Twig extension
-   [#25](https://github.com/cocur/slugify/pull/25) Added Silex service provider

### Version 0.6 (2 April 2014)

-   [#22](https://github.com/cocur/slugify/pull/22) Added support for Esperanto characters (by [Michel Petit](https://github.com/malenkiki))

### Version 0.5 (28 March 2014)

-   [#21](https://github.com/cocur/slugify/pull/21) Added support for Greek characters (by [Michel Petit](https://github.com/malenkiki))
-   [#20](https://github.com/cocur/slugify/pull/20) Fixed rule for cyrillic letter D (by [Marchenko Alexandr](https://github.com/cocur/slugify/pull/20))
-   Add missing `$separator` parameter to `SlugifyInterface`

### Version 0.4.1 (9 March 2014)

-   [#19](https://github.com/cocur/slugify/pull/19) Adds soft sign rule (by [Marchenko Alexandr](https://github.com/mac2000))

### Version 0.4 (17 January 2014)

Nearly completely rewritten code, removes `iconv` support because the underlying library is broken. The code is now better and faster. Many thanks to [Marchenko Alexandr](http://mac-blog.org.ua).

### Version 0.3 (12 January 2014)

-   [#11](https://github.com/cocur/slugify/pull/11) PSR-4 compatible (by [mac2000](https://github.com/mac2000))
-   [#13](https://github.com/cocur/slugify/pull/13) Added editorconfig (by [mac2000](https://github.com/mac2000))
-   [#14](https://github.com/cocur/slugify/pull/14) Return empty slug when input is empty and removed unused parameter (by [mac2000](https://github.com/mac2000))

## Authors

-   [Florian Eckerstorfer](http://florian.ec) ([Twitter](http://twitter.com/Florian_))
-   [Ivo Bathke](https://github.com/ivoba)
-   [Marchenko Alexandr](http://mac-blog.org.ua)
-   And many [great contributors](https://github.com/cocur/slugify/graphs/contributors)

Support for Chinese is adapted from [jifei/Pinyin](https://github.com/jifei/Pinyin) with permission.

> Slugify is a project of [Cocur](http://cocur.co). You can contact us on Twitter:
> [**@cocurco**](https://twitter.com/cocurco)

## Support

If you need support you can ask on [Twitter](https://twitter.com/cocurco) (well, only if your question is short) or you
can join our chat on Gitter.

[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/cocur/slugify)

In case you want to support the development of Slugify you can help us with providing additional transliterations or
inform us if a transliteration is wrong. We would highly appreciate it if you can send us directly a Pull Request on
Github. If you have never contributed to a project on Github we are happy to help you. Just ask on Twitter or directly
join our Gitter.

You always can help me (Florian, the original developer and maintainer) out by
[sending me an Euro or two](https://paypal.me/florianec/2).

## License

The MIT License (MIT)

Copyright (c) 2012-2017 Florian Eckerstorfer

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
