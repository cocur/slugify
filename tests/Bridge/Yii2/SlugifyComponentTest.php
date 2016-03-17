<?php

/**
 * This file is part of cocur/slugify.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Slugify\Bridge\Yii2;

use Mockery as m;

/**
 * SlugifyComponentTest
 *
 * @category   test
 * @package    cocur/slugify
 * @subpackage bridge
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2012-2014 Florian Eckerstorfer
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 * @group      unit
 */
class SlugifyComponentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Cocur\Slugify\SlugifyInterface|\Mockery\MockInterface
     */
    private $slugify;

    /**
     * @var SlugifyComponent
     */
    private $component;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->slugify = m::mock('Cocur\Slugify\SlugifyInterface');
        $this->component = new SlugifyComponent();
        $this->component->setSlugify($this->slugify);
    }

    /**
     * @test
     * @covers \Cocur\Slugify\Bridge\Yii2\SlugifyComponent::slugify()
     */
    public function slugify()
    {
        $this->slugify->shouldReceive('slugify')->with('hällo wörld', '_')->once()->andReturn('haello_woerld');

        $this->assertEquals('haello_woerld', $this->component->slugify('hällo wörld', '_'));
    }
}
