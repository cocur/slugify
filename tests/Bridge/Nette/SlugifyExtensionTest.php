<?php

namespace Cocur\Slugify\Bridge\Nette;

use \Mockery as m;

/**
 * SlugifyExtensionTest
 *
 * @category   test
 * @package    cocur/slugify
 * @subpackage bridge
 * @author     Lukáš Unger <looky.msc@gmail.com>
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 * @group      unit
 */
class SlugifyExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->extension = new SlugifyExtension();
    }

    /**
     * @test
     * @covers Cocur\Slugify\Bridge\Nette\SlugifyExtension::loadConfiguration()
     */
    public function loadConfiguration()
    {
        $slugify = m::mock('Nette\DI\ServiceDefinition');
        $slugify
            ->shouldReceive('setClass')
            ->with('Cocur\Slugify\SlugifyInterface')
            ->once()
            ->andReturn($slugify);
        $slugify
            ->shouldReceive('setFactory')
            ->with('Cocur\Slugify\Slugify')
            ->once()
            ->andReturn($slugify);

        $helper = m::mock('Nette\DI\ServiceDefinition');
        $helper
            ->shouldReceive('setClass')
            ->with('Cocur\Slugify\Bridge\Latte\SlugifyHelper')
            ->once()
            ->andReturn($helper);
        $helper
            ->shouldReceive('setInject')
            ->with(false)
            ->once()
            ->andReturn($helper);

        $builder = m::mock('Nette\DI\ContainerBuilder');
        $builder
            ->shouldReceive('addDefinition')
            ->with('slugify.slugify')
            ->once()
            ->andReturn($slugify);
        $builder
            ->shouldReceive('addDefinition')
            ->with('slugify.helper')
            ->once()
            ->andReturn($helper);

        $compiler = m::mock('Nette\DI\Compiler');
        $compiler
            ->shouldReceive('getContainerBuilder')
            ->once()
            ->andReturn($builder);

        $this->extension->setCompiler($compiler, 'slugify');
        $this->extension->loadConfiguration();
    }

    /**
     * @test
     * @covers Cocur\Slugify\Bridge\Nette\SlugifyExtension::beforeCompile()
     */
    public function beforeCompile()
    {
        $latteFactory = m::mock('Nette\DI\ServiceDefinition');
        $latteFactory
            ->shouldReceive('addSetup')
            ->with('addFilter', array('slugify', array('@slugify.helper', 'slugify')))
            ->once()
            ->andReturn($latteFactory);

        $latte = m::mock('Nette\DI\ServiceDefinition');
        $latte
            ->shouldReceive('addSetup')
            ->with('addFilter', array('slugify', array('@slugify.helper', 'slugify')))
            ->once()
            ->andReturn($latte);

        $builder = m::mock('Nette\DI\ContainerBuilder');
        $builder
            ->shouldReceive('getByType')
            ->with('Nette\Bridges\ApplicationLatte\ILatteFactory')
            ->once()
            ->andReturn('latte.latteFactory');
        $builder
            ->shouldReceive('hasDefinition')
            ->with('latte.latteFactory')
            ->once()
            ->andReturn(true);
        $builder
            ->shouldReceive('getDefinition')
            ->with('latte.latteFactory')
            ->once()
            ->andReturn($latteFactory);
        $builder
            ->shouldReceive('hasDefinition')
            ->with('nette.latte')
            ->once()
            ->andReturn(true);
        $builder
            ->shouldReceive('getDefinition')
            ->with('nette.latte')
            ->once()
            ->andReturn($latte);

        $compiler = m::mock('Nette\DI\Compiler');
        $compiler
            ->shouldReceive('getContainerBuilder')
            ->once()
            ->andReturn($builder);

        $this->extension->setCompiler($compiler, 'slugify');
        $this->extension->beforeCompile();
    }
}
