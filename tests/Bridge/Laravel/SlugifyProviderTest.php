<?php

/**
 * This file is part of cocur/slugify.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Slugify\Tests\Bridge\Laravel;

use Cocur\Slugify\Bridge\Laravel\SlugifyServiceProvider;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;

/**
 * SlugifyServiceProviderTest
 *
 * @category   test
 * @package    cocur/slugify
 * @subpackage bridge
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @author     Colin Viebrock
 * @copyright  2012-2014 Florian Eckerstorfer
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 * @group      unit
 */
class SlugifyProviderTest extends TestCase
{
    /** @var Application */
    private $app;

    /** @var SlugifyServiceProvider */
    private $provider;

    protected function setUp()
    {
        $this->app = new Application();
        $this->provider = new SlugifyServiceProvider($this->app);
    }

    /**
     * @test
     * @covers Cocur\Slugify\Bridge\Laravel\SlugifyServiceProvider::register()
     */
    public function registerRegistersTheServiceProvider()
    {
        $this->provider->register();

        // the service provider is deferred, so this forces it to load
        $this->app->make('slugify');

        $this->assertArrayHasKey('slugify', $this->app);
        $this->assertInstanceOf('Cocur\Slugify\Slugify', $this->app['slugify']);
    }

    /**
     * @test
     * @covers Cocur\Slugify\Bridge\Laravel\SlugifyServiceProvider::provides()
     */
    public function containsReturnsTheNameOfThProvider()
    {
        $this->assertContains('slugify', $this->provider->provides());
    }
}
