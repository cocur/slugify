<?php

namespace Cocur\Slugify\Bridge\Latte;

use Cocur\Slugify\SlugifyInterface;

/**
 * SlugifyHelper
 *
 * @package    cocur/slugify
 * @subpackage bridge
 * @author     Lukáš Unger <looky.msc@gmail.com>
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class SlugifyHelper
{
    /** @var SlugifyInterface */
    private $slugify;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(SlugifyInterface $slugify)
    {
        $this->slugify = $slugify;
    }

    /**
     * @param string $string
     * @param string $separator
     *
     * @return string
     */
    public function slugify($string, $separator = '-')
    {
        return $this->slugify->slugify($string, $separator);
    }
}
