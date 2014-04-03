<?php

namespace Cocur\Slugify\Bridge\Bundle;

use Cocur\Slugify\Bridge\Bundle\CocurSlugifyExtension;
use \Mockery as m;


/**
 * CocurSlugifyExtensionTest
 *
 * @group unit
 */
class CocurSlugifyExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->extension = new CocurSlugifyExtension();
    }

    /**
     * @test
     * @covers Cocur\Slugify\Bridge\Bundle\CocurSlugifyExtension::load()
     */
    public function load()
    {
        $twigDefinition = m::mock('Symfony\Component\DependencyInjection\Definition');
        $twigDefinition->shouldReceive('addTag')->with('twig.extension')->once();

        $container = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $container
            ->shouldReceive('setDefinition')
            ->with('cocur_slugify', m::type('Symfony\Component\DependencyInjection\Definition'))
            ->once();
        $container
            ->shouldReceive('setDefinition')
            ->with('cocur_slugify.twig.slugify', m::type('Symfony\Component\DependencyInjection\Definition'))
            ->once()
            ->andReturn($twigDefinition);

        $this->extension->load(array(), $container);
    }
}

