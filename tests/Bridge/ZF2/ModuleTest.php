<?php
namespace Cocur\Slugify\Tests\Bridge\ZF2;

use Cocur\Slugify\Bridge\ZF2\Module;
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
     * @covers \Cocur\Slugify\Bridge\ZF2\Module::getServiceConfig()
     */
    public function testGetServiceConfig()
    {
        $smConfig = $this->module->getServiceConfig();
        $this->assertIsArray($smConfig);
        $this->assertArrayHasKey('factories', $smConfig);
        $this->assertArrayHasKey('Cocur\Slugify\Slugify', $smConfig['factories']);
        $this->assertArrayHasKey('aliases', $smConfig);
        $this->assertArrayHasKey('slugify', $smConfig['aliases']);
    }

    /**
     * @covers \Cocur\Slugify\Bridge\ZF2\Module::getViewHelperConfig()
     */
    public function testGetViewHelperConfig()
    {
        $vhConfig = $this->module->getViewHelperConfig();
        $this->assertIsArray($vhConfig);
        $this->assertArrayHasKey('factories', $vhConfig);
        $this->assertArrayHasKey('slugify', $vhConfig['factories']);
    }
}
