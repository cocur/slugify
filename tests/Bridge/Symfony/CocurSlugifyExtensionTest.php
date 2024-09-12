<?php

/**
 * This file is part of cocur/slugify.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Slugify\Tests\Bridge\Symfony;

use Cocur\Slugify\Bridge\Symfony\CocurSlugifyExtension;
use Cocur\Slugify\SlugifyInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * CocurSlugifyExtensionTest
 *
 * @category   test
 * @package    cocur/slugify
 * @subpackage bridge
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2012-2014 Florian Eckerstorfer
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 * @group      unit
 */
class CocurSlugifyExtensionTest extends TestCase
{
    protected function setUp(): void
    {
        $this->extension = new CocurSlugifyExtension();
    }

    /**
     * @covers \Cocur\Slugify\Bridge\Symfony\CocurSlugifyExtension::load()
     */
    public function testLoad()
    {
        $twigDefinition = $this->createMock(Definition::class);
        $twigDefinition
            ->expects($this->once())
            ->method('addTag')
            ->with($this->equalTo('twig.extension'))
            ->willReturn($twigDefinition);
        $twigDefinition
            ->expects($this->once())
            ->method('setPublic')
            ->with($this->equalTo(false))
            ->willReturn($twigDefinition);

        $container = $this->createMock(ContainerBuilder::class);
        $container
            ->expects($this->exactly(2))
            ->method('setDefinition')
            ->withConsecutive(
                [$this->equalTo('cocur_slugify'), $this->isInstanceOf(Definition::class)],
                [$this->equalTo('cocur_slugify.twig.slugify'), $this->isInstanceOf(Definition::class)]
            )
            ->willReturnOnConsecutiveCalls(
                $this->createMock(Definition::class),
                $twigDefinition
            );
        $container
            ->expects($this->exactly(2))
            ->method('setAlias')
            ->withConsecutive(
                [$this->equalTo('slugify'), $this->equalTo('cocur_slugify')],
                [$this->equalTo(SlugifyInterface::class), $this->equalTo('cocur_slugify')]
            );
        $this->extension->load([], $container);
    }
}
