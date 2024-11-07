<?php

namespace Cocur\Slugify\Bridge\Laminas;

use Cocur\Slugify\Slugify;
use Laminas\Filter\FilterInterface;

/**
 * Class SlugifyFilter
 *
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyFilter implements FilterInterface
{
    /**
     * @var array
     * @see Slugify::$options
     */
    protected array $options = [
        'regexp' => Slugify::LOWERCASE_NUMBERS_DASHES,
        'separator' => '-',
        'lowercase' => true,
        'lowercase_after_regexp' => false,
        'trim' => true,
        'strip_tags' => false
    ];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (!empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     *
     * @return mixed
     */
    public function filter($value)
    {
        if (!empty($value)) {
            $slugify = new Slugify($this->options);
            return $slugify->slugify((string) $value);
        }

        return $value;
    }

    /**
     * @param array $options
     *
     * @return void
     */
    protected function setOptions(array $options)
    {
        foreach ($options as $key => $option) {
            if (array_key_exists($key, $this->options)) {
                $this->options[$key] = $option;
            }
        }
    }
}
