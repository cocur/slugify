<?php

/**
 * This file is part of cocur/slugify.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Slugify\Tests\Bridge\Twig;

use Cocur\Slugify\Bridge\Twig\SlugifyExtension;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * SlugifyExtensionTest
 *
 * @category   test
 * @package    cocur/slugify
 * @subpackage bridge
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2012-2014 Florian Eckerstorfer
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 * @group      unit
 */
class SlugifyExtensionTest extends MockeryTestCase
{
    /**
     * @var \Cocur\Slugify\SlugifyInterface|\Mockery\MockInterface
     */
    protected $slugify;

    /**
     * @var SlugifyExtension
     */
    protected $extension;

    protected function setUp(): void
    {
        $this->slugify = m::mock('Cocur\Slugify\SlugifyInterface');
        $this->extension = new SlugifyExtension($this->slugify);
    }

    /**
     * @covers \Cocur\Slugify\Bridge\Twig\SlugifyExtension::getFilters()
     */
    public function testGetFilters()
    {
        $filters = $this->extension->getFilters();

        $this->assertCount(1, $filters);
        $this->assertInstanceOf('\Twig\TwigFilter', $filters[0]);
    }

    /**
     * @covers \Cocur\Slugify\Bridge\Twig\SlugifyExtension::slugifyFilter()
     */
    public function testSlugifyFilter()
    {
        $this->slugify->shouldReceive('slugify')->with('hällo wörld', '_')->once()->andReturn('haello_woerld');

        $this->assertSame('haello_woerld', $this->extension->slugifyFilter('hällo wörld', '_'));
    }
}
