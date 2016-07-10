<?php

/*
 * This file is part of the cocur/slugify package.
 *
 * (c) Enrico Stahn <enrico.stahn@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Slugify\Tests\Bridge\Symfony;

use Cocur\Slugify\Bridge\Symfony\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        $configs = [
            [
                'lowercase' => true,
                'separator' => '_',
                'regexp' => 'abcd',
                'rulesets' => ['burmese', 'hindi']
            ],
        ];

        $this->process($configs);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidTypeException
     */
    public function testLowercaseOnlyAcceptsBoolean()
    {
        $configs = [['lowercase' => 'abc']];
        $this->process($configs);
    }

    /**
     * Processes an array of configurations and returns a compiled version.
     *
     * @param array $configs An array of raw configurations
     *
     * @return array A normalized array
     */
    protected function process($configs)
    {
        $processor = new Processor();

        return $processor->processConfiguration(new Configuration(), $configs);
    }
}
