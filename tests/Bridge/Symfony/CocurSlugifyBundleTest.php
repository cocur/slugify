<?php

/**
 * This file is part of cocur/slugify.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Slugify\Bridge\Symfony;

use Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle;

/**
 * CocurSlugifyBundleTest
 *
 * @category   test
 * @package    cocur/slugify
 * @subpackage bridge
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2012-2014 Florian Eckerstorfer
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 * @group      unit
 */
class CocurSlugifyBundleTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->bundle = new CocurSlugifyBundle();
    }

    /**
     * @test
     * @covers Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle::build()
     */
    public function build()
    {
        $container = $this->getMock(
            'Symfony\Component\DependencyInjection\ContainerBuilder',
            array('registerExtension')
        );
        $container->expects($this->once())
            ->method('registerExtension')
            ->with($this->isInstanceOf('Cocur\Slugify\Bridge\Symfony\CocurSlugifyExtension'));

        $this->bundle->build($container);
    }
}

