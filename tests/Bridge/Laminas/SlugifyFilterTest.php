<?php

namespace Cocur\Slugify\Tests\Bridge\Laminas;

use Cocur\Slugify\Bridge\Laminas\SlugifyFilter;

use Laminas\Filter\FilterChain;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class SlugifyFilterTest
 *
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyFilterTest extends MockeryTestCase
{
    /**
     * @covers \Cocur\Slugify\Bridge\Laminas\SlugifyFilter::filter()
     */
    public function testSlugifyFilterWithoutCustomOptions()
    {
        $chain = new FilterChain(
            [
                'filters'    => [
                    [
                        'name' => SlugifyFilter::class,
                    ],
                ],
            ]
        );

        $value = 'foo Bar';
        $expected = 'foo-bar';

        $this->assertSame($expected, $chain->filter($value));

    }

    /**
     * @covers \Cocur\Slugify\Bridge\Laminas\SlugifyFilter::filter()
     */
    public function testSlugifyFilterWithCustomOptions()
    {
        $chain = new FilterChain(
            [
                'filters'    => [
                    [
                        'name' => SlugifyFilter::class,
                        'options' => [
                            'regexp' => '/([^0-9test\/]|-)+/',
                            'strip_tags' => false,
                        ]
                    ],
                ],
            ]
        );

        $value = '0123 foo bar <test>';
        $expected = '0123-test';

        $this->assertSame($expected, $chain->filter($value));
    }

}
