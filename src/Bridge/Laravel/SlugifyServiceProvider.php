<?php

/**
 * This file is part of cocur/slugify.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Slugify\Bridge\Laravel;

use Cocur\Slugify\Slugify;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * SlugifyServiceProvider
 *
 * @package    cocur/slugify
 * @subpackage bridge
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @author     Colin Viebrock
 * @copyright  2012-2014 Florian Eckerstorfer
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyServiceProvider extends LaravelServiceProvider implements DeferrableProvider
{
    /**
     * Indicates if loading of the provider is deferred. ( Deprecated : Laravel 5.8 )
     *
     * @var bool
     */
    // protected $defer = true; ( Deprecated : Laravel 5.8 )

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('slugify', function () {
            return new Slugify();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['slugify'];
    }
}
