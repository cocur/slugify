<?php

namespace Cocur\Slugify\Bridge\Bundle;

use Cocur\Slugify\Bridge\Bundle\CocurSlugifyBundle;

/**
 * CocurSlugifyBundleTest
 *
 * @group unit
 */
class CocurSlugifyBundleTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->bundle = new CocurSlugifyBundle();
    }

    /**
     * @test
     * @covers Cocur\Slugify\Bridge\Bundle\CocurSlugifyBundle::build()
     */
    public function build()
    {
        $container = $this->getMock(
            'Symfony\Component\DependencyInjection\ContainerBuilder',
            array('registerExtension')
        );
        $container->expects($this->once())
            ->method('registerExtension')
            ->with($this->isInstanceOf('Cocur\Slugify\Bridge\Bundle\CocurSlugifyExtension'));

        $this->bundle->build($container);
    }
}

