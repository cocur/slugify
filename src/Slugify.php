<?php

/**
 * This file is part of cocur/slugify.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Slugify;

use Cocur\Slugify\Transliter\TransliterFactory;

/**
 * Slugify
 *
 * @package   org.cocur.slugify
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @author    Ivo Bathke <ivo.bathke@gmail.com>
 * @copyright 2012-2014 Florian Eckerstorfer
 * @license   http://www.opensource.org/licenses/MIT The MIT License
 */
class Slugify implements SlugifyInterface
{
    const MODE_ICONV = 'iconv';
    const MODE_ARRAYMAP = 'arraymap';

    /** @var TransliterInterface */
    private $transliter;

    /**
     * Static method to create new instance of {@see Slugify}.
     *
     * @param TransliterInterface|string|null $transliter Transliter
     *
     * @return Slugify
     */
    public static function create($transliter = null)
    {
        return new static($transliter);
    }

    /**
     * Constructor.
     *
     * @param TransliterInterface|string|null $transliter Transliter
     */
    public function __construct($transliter = null)
    {
        if (null === $transliter && true === function_exists('iconv')) {
            $transliter = Slugify::MODE_ICONV;
        } else if (null === $transliter) {
            $transliter = Slugify::MODE_ARRAYMAP;
        }

        if (false === ($transliter instanceof TransliterInterface)) {
            $transliter = TransliterFactory::create($transliter);
        }

        $this->transliter = $transliter;
    }

    /**
     * Takes a string and returns a slugified version of it.
     *
     * Slugs only consists of characters, numbers and the dash. They can be used in URLs.
     *
     * @param string $string    Input string
     * @param string $separator Separator
     *
     * @return string Slugified version of the input string
     */
    public function slugify($string, $separator = '-')
    {
        if (true === empty($string)) {
            return '';
        }

        $string = preg_replace(
            '/
                [\x09\x0A\x0D\x20-\x7E]              # ASCII
                | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
                |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
                | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
                |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
                |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
                | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
                |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
            /',
            '',
            $string
        );

        // transliterate
        $string = $this->transliter->translit($string);

        // replace non letter or digits by seperator
        $string = preg_replace('#[^\\pL\d]+#u', $separator, $string);
        $string = trim($string, $separator);

        // Convert slug into lowercase
        $string = $this->stringToLower($string);

        // remove unwanted characters
        $string = preg_replace('#[^-\w]+#', '', $string);

        return $string;
    }

    /**
     * Returns the string in lowercase characters. Uses mb_strtolower when available.
     *
     * @param string $input Input string.
     *
     * @return string String in lowercase characters.
     */
    protected function stringToLower($input)
    {
        return true === defined('MB_CASE_LOWER') ? mb_strtolower($input) : strtolower($input);
    }
}
