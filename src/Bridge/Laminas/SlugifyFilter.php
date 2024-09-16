<?php

namespace Cocur\Slugify\Bridge\Laminas;

use Cocur\Slugify\Slugify;
use Laminas\Filter\AbstractFilter;

/**
 * Class SlugifyFilter
 *
 * @package    cocur/slugify
 * @subpackage bridge
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyFilter extends AbstractFilter
{
    /**
     * @var array
     * @see Slugify::$options
     */
    protected $options = [
        'regexp' => Slugify::LOWERCASE_NUMBERS_DASHES,
        'separator' => '-',
        'lowercase' => true,
        'lowercase_after_regexp' => false,
        'trim' => true,
        'strip_tags' => false
    ];

    /**
     * @param array|null $options
     */
    public function __construct(?array $options = null)
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
}
