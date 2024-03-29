<?php

namespace Cocur\Slugify\Tests\Bridge\Latte;

use Cocur\Slugify\Bridge\Latte\SlugifyHelper;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * SlugifyHelperTest
 *
 * @category   test
 * @package    cocur/slugify
 * @subpackage bridge
 * @author     Lukáš Unger <looky.msc@gmail.com>
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 * @group      unit
 */
class SlugifyHelperTest extends MockeryTestCase
{
    protected function setUp(): void
    {
        $this->slugify = m::mock('Cocur\Slugify\SlugifyInterface');
        $this->helper = new SlugifyHelper($this->slugify);
    }

    /**
     * @covers \Cocur\Slugify\Bridge\Latte\SlugifyHelper::slugify()
     */
    public function testSlugify()
    {
        $this->slugify->shouldReceive('slugify')->with('hällo wörld', '_')->once()->andReturn('haello_woerld');

        $this->assertSame('haello_woerld', $this->helper->slugify('hällo wörld', '_'));
    }
}
