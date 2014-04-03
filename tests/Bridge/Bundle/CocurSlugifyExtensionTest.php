<?php

namespace Cocur\Slugify\Bridge\Bundle;

use Cocur\Slugify\Bridge\Bundle\CocurSlugifyExtension;

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
        $container = $this->getMock(
            'Symfony\Component\DependencyInjection\ContainerBuilder',
            array('setDefinition')
        );
        $container->expects($this->once())
            ->method('setDefinition')
            ->with('cocur_slugify', $this->isInstanceOf('Symfony\Component\DependencyInjection\Definition'));

        $this->extension->load(array(), $container);
    }
}

