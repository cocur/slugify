<?php

/**
 * This file is part of cocur/slugify.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Slugify\Bridge\Silex;

use Cocur\Slugify\Bridge\Silex\SlugifyServiceProvider;

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
    /** @var SlugifyServiceProvider */
    private $provider;

    public function setUp()
    {
        $this->provider = new SlugifyServiceProvider();
    }

    /**
     * @test
     * @covers Cocur\Slugify\Bridge\Silex\SlugifyServiceProvider::boot()
     */
    public function boot()
    {
        // it seems like Application is not mockable.
        $app = new \Silex\Application();
        $this->provider->boot($app);
    }

    /**
     * @test
     * @covers Cocur\Slugify\Bridge\Silex\SlugifyServiceProvider::register()
     */
    public function register()
    {
        // it seems like Application is not mockable.
        $app = new \Silex\Application();
        $this->provider->register($app);

        $this->assertArrayHasKey('slugify', $app);
        $this->assertInstanceOf('Cocur\Slugify\Slugify', $app['slugify']);
    }
}
