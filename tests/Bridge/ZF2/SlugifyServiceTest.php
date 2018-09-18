<?php
namespace Cocur\Slugify\Tests\Bridge\ZF2;

use Cocur\Slugify\Bridge\ZF2\Module;
use Cocur\Slugify\Bridge\ZF2\SlugifyService;
use Zend\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

/**
 * Class SlugifyServiceTest
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyServiceTest extends TestCase
{
    /**
     * @var SlugifyService
     */
    private $slugifyService;

    protected function setUp()
    {
        $this->slugifyService = new SlugifyService();
    }

    /**
     * @test
     * @covers Cocur\Slugify\Bridge\ZF2\SlugifyService::__invoke()
     */
    public function invokeWithoutCustomConfig()
    {
        $sm = $this->createServiceManagerMock();
        $slugify = call_user_func($this->slugifyService, $sm);
        $this->assertInstanceOf('Cocur\Slugify\Slugify', $slugify);

        // Make sure reg exp is default one
        $actual = 'Hello My Friend.zip';
        $expected = 'hello-my-friend-zip';
        $this->assertEquals($expected, $slugify->slugify($actual));
    }

    /**
     * @test
     * @covers Cocur\Slugify\Bridge\ZF2\SlugifyService::__invoke()
     */
    public function invokeWithCustomConfig()
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
        $this->assertEquals($expected, $slugify->slugify($actual));
    }

    protected function createServiceManagerMock(array $config = [])
    {
        $sm = new ServiceManager();
        $sm->setService('Config', $config);

        return $sm;
    }
}
