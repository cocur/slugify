<?php
namespace Cocur\Slugify\Tests\Bridge\Laminas;

use Cocur\Slugify\Bridge\Laminas\SlugifyViewHelperFactory;
use Cocur\Slugify\Slugify;
use Laminas\ServiceManager\ServiceManager;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class SlugifyViewHelperFactoryTest
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyViewHelperFactoryTest extends MockeryTestCase
{
    /**
     * @var SlugifyViewHelperFactory
     */
    private $factory;

    protected function setUp(): void
    {
        $this->factory = new SlugifyViewHelperFactory();
    }

    /**
     * @covers \Cocur\Slugify\Bridge\Laminas\SlugifyViewHelperFactory::__invoke()
     */
    public function testCreateService()
    {
        $sm = new ServiceManager();
        $sm->setService('Cocur\Slugify\Slugify', new Slugify());

        $viewHelper = call_user_func($this->factory, $sm);
        $this->assertInstanceOf('Cocur\Slugify\Bridge\Laminas\SlugifyViewHelper', $viewHelper);
    }
}
