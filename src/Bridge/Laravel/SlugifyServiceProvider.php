<?php namespace Cocur\Slugify\Bridge\Laravel;

use Cocur\Slugify\Slugify;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Foundation\Application;


/**
 * SlugifyServiceProvider
 *
 * @package    cocur/slugify
 * @subpackage bridge
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2012-2014 Florian Eckerstorfer
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyServiceProvider extends LaravelServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->bindShared('slugify', function (Application $app) {
            return new Slugify();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array('slugify');
    }

}
