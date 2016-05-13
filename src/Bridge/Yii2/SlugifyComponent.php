<?php

/**
 * This file is part of cocur/slugify.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Slugify\Bridge\Yii2;

use Cocur\Slugify\SlugifyInterface;
use yii\base\Component;
use Cocur\Slugify\Slugify;

/**
 * SlugifyConverter
 *
 * @package   Cocur\Slugify\Bridge\Yii2
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2015 Florian Eckerstorfer
 */
class SlugifyComponent extends Component
{
    /** @var Slugify */
    private $slugify;

    /**
     * @var array
     */
    public $options = [];

    /**
     */
    public function init()
    {
        $this->slugify = new Slugify($this->options);
    }

    /**
     * Return a URL safe version of a string.
     *
     * @param string $string
     * @param string $separator
     *
     * @return string
     *
     * @api
     */
    public function slugify($string, $separator = '-')
    {
        return $this->slugify->slugify($string, $separator);
    }

    /**
     * @param SlugifyInterface $slugify
     */
    public function setSlugify($slugify)
    {
        $this->slugify = $slugify;
    }
}
