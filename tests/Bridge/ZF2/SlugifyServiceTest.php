<?php
namespace Cocur\Slugify\Tests\Bridge\ZF2;

use Cocur\Slugify\Bridge\ZF2\Module;
use Cocur\Slugify\Bridge\ZF2\SlugifyService;
use Zend\ServiceManager\ServiceManager;
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
     * @covers \Cocur\Slugify\Bridge\ZF2\SlugifyService::__invoke()
     */
    public function testInvokeWithoutCustomConfig()
    {
        $sm = $this->createServiceManagerMock();
        $slugify = call_user_func($this->slugifyService, $sm);
        $this->assertInstanceOf('Cocur\Slugify\Slugify', $slugify);

        // Make sure reg exp is default one
        $actual = 'Hello My Friend.zip';
        $expected = 'hello-my-friend-zip';
        $this->assertSame($expected, $slugify->slugify($actual));
    }

    /**
     * @covers \Cocur\Slugify\Bridge\ZF2\SlugifyService::__invoke()
     */
    public function testInvokeWithCustomConfig()
    {
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
        $sm = new ServiceManager();
        $sm->setService('Config', $config);

        return $sm;
    }
}
