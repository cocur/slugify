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
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\Config\Definition\Processor;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class ConfigurationTest extends MockeryTestCase
{
    public function testAll()
    {
        $configs = [
            [
                'lowercase' => true,
                'lowercase_after_regexp' => false,
                'strip_tags' => false,
                'separator' => '_',
                'regexp' => 'abcd',
                'rulesets' => ['burmese', 'hindi']
            ],
        ];

        $this->assertSame($configs[0], $this->process($configs));
    }

    public function testLowercaseOnlyAcceptsBoolean()
    {
        $this->expectException(InvalidTypeException::class);

        $configs = [['lowercase' => 'abc']];
        $this->process($configs);
    }

    public function testLowercaseAfterRegexpOnlyAcceptsBoolean()
    {
        $this->expectException(InvalidTypeException::class);

        $configs = [['lowercase_after_regexp' => 'abc']];
        $this->process($configs);
    }

    public function testStripTagsOnlyAcceptsBoolean()
    {
        $this->expectException(InvalidTypeException::class);

        $configs = [['strip_tags' => 'abc']];
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
