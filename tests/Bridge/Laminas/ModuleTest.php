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
     * @covers \Cocur\Slugify\Bridge\Laminas\Module::getServiceConfig()
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
        $this->assertArrayHasKey(SlugifyViewHelper::class, $vhConfig['factories']);
        $this->assertArrayHasKey('aliases', $vhConfig);
        $this->assertArrayHasKey('slugify', $vhConfig['aliases']);
    }
}
