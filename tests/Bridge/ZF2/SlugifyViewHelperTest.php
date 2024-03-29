<?php
namespace Cocur\Slugify\Tests\Bridge\ZF2;

use Cocur\Slugify\Bridge\ZF2\SlugifyViewHelper;
use Cocur\Slugify\Slugify;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class SlugifyViewHelperTest
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyViewHelperTest extends MockeryTestCase
{
    /**
     * @var SlugifyViewHelper
     */
    private $viewHelper;
    /**
     * @var Slugify
     */
    private $slugify;

    /**
     * @covers \Cocur\Slugify\Bridge\ZF2\SlugifyViewHelper::__construct()
     */
    protected function setUp(): void
    {
        $this->slugify = new Slugify();
        $this->viewHelper = new SlugifyViewHelper($this->slugify);
    }

    /**
     * @covers \Cocur\Slugify\Bridge\ZF2\SlugifyViewHelper::__invoke()
     */
    public function testInvoke()
    {
        $actual = 'Hällo Wörld';
        $expected = call_user_func($this->viewHelper, $actual);
        $this->assertEquals($expected, $this->slugify->slugify($actual));

        $expected = call_user_func($this->viewHelper, $actual, '_');
        $this->assertEquals($expected, $this->slugify->slugify($actual, '_'));
    }
}
