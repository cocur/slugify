<?php
namespace Cocur\Slugify\Tests\Bridge\Laminas;

use Cocur\Slugify\Bridge\Laminas\Module;
use Cocur\Slugify\Bridge\Laminas\SlugifyService;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\ServiceManager;

use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class SlugifyServiceTest
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyServiceTest extends MockeryTestCase
{
    /**
     * @var SlugifyService
     */
    private $slugifyService;

    protected function setUp(): void
    {

        $this->slugifyService = new SlugifyService();
    }

    /**
     * @covers \Cocur\Slugify\Bridge\Laminas\SlugifyService::__invoke()
     */
    public function testInvokeWithoutCustomConfig()
    {
        $this->markTestSkipped();
        $sm = $this->createServiceManagerMock();
        $slugify = call_user_func($this->slugifyService, $sm);
        $this->assertInstanceOf('Cocur\Slugify\Slugify', $slugify);

        // Make sure reg exp is default one
        $actual = 'Hello My Friend.zip';
        $expected = 'hello-my-friend-zip';
        $this->assertSame($expected, $slugify->slugify($actual));
    }

    /**
     * @covers \Cocur\Slugify\Bridge\Laminas\SlugifyService::__invoke()
     */
    public function testInvokeWithCustomConfig()
    {
        $this->markTestSkipped();
        $sm = $this->createServiceManagerMock([
            Module::CONFIG_KEY => [
                'options' => ['regexp' => '/([^a-z0-9.]|-)+/']
            ]
        ]);
        $slugify = call_user_func($this->slugifyService, $sm);
        $this->assertInstanceOf('Cocur\Slugify\Slugify', $slugify);

        // Make sure reg exp is the one provided and dots are kept
        $actual = 'Hello My Friend.zip';
        $expected = 'hello-my-friend.zip';
        $this->assertSame($expected, $slugify->slugify($actual));
    }

    protected function createServiceManagerMock(array $config = [])
    {
        $sm = new ServiceManager($config);
        //$sm->setService('Config', $config);

        return $sm;
    }
}
