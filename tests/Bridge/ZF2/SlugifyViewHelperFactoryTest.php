<?php
namespace Cocur\Slugify\Tests\Bridge\ZF2;

use Cocur\Slugify\Bridge\ZF2\SlugifyViewHelperFactory;
use Cocur\Slugify\Slugify;
use Zend\ServiceManager\ServiceManager;
use Zend\View\HelperPluginManager;
use PHPUnit\Framework\TestCase;

/**
 * Class SlugifyViewHelperFactoryTest
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyViewHelperFactoryTest extends TestCase
{
    /**
     * @var SlugifyViewHelperFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new SlugifyViewHelperFactory();
    }

    /**
     * @test
     * @covers Cocur\Slugify\Bridge\ZF2\SlugifyViewHelperFactory::__invoke()
     */
    public function createService()
    {
        $sm = new ServiceManager();
        $sm->setService('Cocur\Slugify\Slugify', new Slugify());
        $vhm = new HelperPluginManager();
        $vhm->setServiceLocator($sm);

        $viewHelper = call_user_func($this->factory, $vhm);
        $this->assertInstanceOf('Cocur\Slugify\Bridge\ZF2\SlugifyViewHelper', $viewHelper);
    }
}
