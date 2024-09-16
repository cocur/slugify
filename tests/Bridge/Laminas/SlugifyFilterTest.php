<?php

namespace Cocur\Slugify\Tests\Bridge\Laminas;

use Cocur\Slugify\Bridge\Laminas\SlugifyFilter;

use Laminas\Filter\FilterChain;
use Laminas\InputFilter\Input;
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
    /** @var Input */
    protected Input $input;

    protected function setUp(): void
    {
        $this->input = new Input('');
    }

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
        $this->input->setFilterChain($chain);
        $this->input->setValue('foo Bar');

        $this->assertSame('foo-bar', $this->input->getValue());

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
        $this->input->setFilterChain($chain);
        $this->input->setValue('0123 foo bar <test>');

        $this->assertSame('0123-test', $this->input->getValue());
    }

}
