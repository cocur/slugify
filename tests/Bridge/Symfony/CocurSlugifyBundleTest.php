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
    /**
     * @covers Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle::getContainerExtension()
     */
    public function testGetContainerExtension()
    {
        $bundle = new CocurSlugifyBundle();

        static::assertInstanceOf(CocurSlugifyExtension::class, $bundle->getContainerExtension());
    }
}

