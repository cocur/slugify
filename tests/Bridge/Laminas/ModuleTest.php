<?php
namespace Cocur\Slugify\Tests\Bridge\Laminas;

use Cocur\Slugify\Bridge\Laminas\Module;
use Cocur\Slugify\Bridge\Laminas\SlugifyViewHelper;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class ModuleTest
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class ModuleTest extends MockeryTestCase
{
    /**
     * @var Module
     */
    private $module;

    protected function setUp(): void
    {
        $this->module = new Module();
    }

    /**
     * @covers \Cocur\Slugify\Bridge\Laminas\Module::getDependencyConfig()
     */
    public function testGetServiceConfig()
    {
        $smConfig = $this->module->getConfig();
        $this->assertIsArray($smConfig);
        $this->assertArrayHasKey('service_manager', $smConfig);
        $this->assertArrayHasKey('factories', $smConfig['service_manager']);
        $this->assertArrayHasKey('Cocur\Slugify\Slugify', $smConfig['service_manager']['factories']);
        $this->assertArrayHasKey('aliases', $smConfig['service_manager']);
        $this->assertArrayHasKey('slugify', $smConfig['service_manager']['aliases']);
    }

    /**
     * @covers \Cocur\Slugify\Bridge\ZF2\Module::getViewHelperConfig()
     */
    public function testGetViewHelperConfig()
    {
        $vhConfig = $this->module->getConfig();
        $this->assertIsArray($vhConfig);
        $this->assertArrayHasKey('view_helpers', $vhConfig);
        $this->assertArrayHasKey('factories', $vhConfig['view_helpers']);
        $this->assertArrayHasKey(SlugifyViewHelper::class, $vhConfig['view_helpers']['factories']);
        $this->assertArrayHasKey('aliases', $vhConfig['view_helpers']);
        $this->assertArrayHasKey('slugify', $vhConfig['view_helpers']['aliases']);
    }
}
