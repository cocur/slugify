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

use Cocur\Slugify\Bridge\Laravel\SlugifyServiceProvider;

/**
 * SlugifyServiceProviderTest
 *
 * @category   test
 * @package    cocur/slugify
 * @subpackage bridge
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2012-2014 Florian Eckerstorfer
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 * @group      unit
 */
class SlugifyServiceProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @covers Cocur\Slugify\Bridge\Laravel\SlugifyServiceProvider::register()
     */
    public function register()
    {
        // it seems like Application is not mockable.
        $app = new \Illuminate\Foundation\Application();
        $provider = new SlugifyServiceProvider($app);
        $provider->register();
        // the service provider is deferred, so this forces it to load
        $slugify = $app->make('slugify');

        $this->assertArrayHasKey('slugify', $app);
        $this->assertInstanceOf('Cocur\Slugify\Slugify', $app['slugify']);
    }
}
