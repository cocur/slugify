<?php

/**
 * The MIT License (MIT)
 * Copyright (c) 2012 Florian Eckerstorfer
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @package   org.cocur.slugify
 */

namespace Cocur\Slugify;

/**
 * @package   org.cocur.slugify
 * @author    Ivo Bathke <ivo.bathke@gmail.com>
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @copyright 2012 Florian Eckerstorfer
 * @license   http://www.opensource.org/licenses/MIT The MIT License
 */
class Slugify {
    
    const MODEICONV = 'iconv';
    const MODEARRAY = 'array';

    /** 
     * this is modified and will translit german umlauts to ae,etc and not simply to a. 
     * 
     * @var array 
     */
    private static $ascii = array(
        '/º|°/' => 0,
	'/¹/' => 1,
	'/²/' => 2,
	'/³/' => 3,
        '/æ|ǽ|ä/' => 'ae',
        '/œ|ö/' => 'oe',
        '/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ|А/' => 'A',
        '/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª|а/' => 'a',
        '/@/' => 'at',
        '/Б/' => 'B',
        '/б/' => 'b',
        '/Ç|Ć|Ĉ|Ċ|Č|Ц/' => 'C',
        '/ç|ć|ĉ|ċ|č|ц/' => 'c',
        '/Ð|Ď|Đ|Д/' => 'Dj',
        '/ð|ď|đ|д/' => 'dj',
        '/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě|Е|Ё|Э/' => 'E',
        '/è|é|ê|ë|ē|ĕ|ė|ę|ě|е|ё|э/' => 'e',
        '/Ф/' => 'F',
        '/ƒ|ф/' => 'f',
        '/Ĝ|Ğ|Ġ|Ģ|Г/' => 'G',
        '/ĝ|ğ|ġ|ģ|г/' => 'g',
        '/Ĥ|Ħ|Х/' => 'H',
        '/ĥ|ħ|х/' => 'h',
        '/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|И/' => 'I',
        '/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|и/' => 'i',
        '/Ĵ|Й/' => 'J',
        '/ĵ|й/' => 'j',
        '/Ķ|К/' => 'K',
        '/ķ|к/' => 'k',
        '/Ĺ|Ļ|Ľ|Ŀ|Ł|Л/' => 'L',
        '/ĺ|ļ|ľ|ŀ|ł|л/' => 'l',
        '/М/' => 'M',
        '/м/' => 'm',
        '/Ñ|Ń|Ņ|Ň|Н/' => 'N',
        '/ñ|ń|ņ|ň|ŉ|н/' => 'n',
        '/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ|О/' => 'O',
        '/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º|о/' => 'o',
        '/П/' => 'P',
        '/п/' => 'p',
        '/Ŕ|Ŗ|Ř|Р/' => 'R',
        '/ŕ|ŗ|ř|р/' => 'r',
        '/Ś|Ŝ|Ş|Ș|Š|С/' => 'S',
        '/ś|ŝ|ş|ș|š|ſ|с/' => 's',
        '/Ţ|Ț|Ť|Ŧ|Т/' => 'T',
        '/ţ|ț|ť|ŧ|т/' => 't',
        '/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ|У/' => 'U',
        '/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|у/' => 'u',
        '/ü/' => 'ue',
        '/Ü/' => 'UE',
        '/В/' => 'V',
        '/в/' => 'v',
        '/Ý|Ÿ|Ŷ|Ы/' => 'Y',
        '/ý|ÿ|ŷ|ы/' => 'y',
        '/Ŵ/' => 'W',
        '/ŵ/' => 'w',
        '/Ź|Ż|Ž|З/' => 'Z',
        '/ź|ż|ž|з/' => 'z',
        '/Æ|Ǽ|Ä/' => 'AE',
        '/ß/' => 'ss',
        '/Ĳ/' => 'IJ',
        '/ĳ/' => 'ij',
        '/Œ|Ö/' => 'OE',
        '/Ч/' => 'Ch',
        '/ч/' => 'ch',
        '/Ю/' => 'Ju',
        '/ю/' => 'ju',
        '/Я/' => 'Ja',
        '/я/' => 'ja',
        '/Ш/' => 'Sh',
        '/ш/' => 'sh',
        '/Щ/' => 'Shch',
        '/щ/' => 'shch',
        '/Ж/' => 'Zh',
        '/ж/' => 'zh',
    );

    /**
     * possible slugify methods
     * 
     * @var iconv|array 
     */
    protected $mode = Slugify::MODEICONV;

    public function __construct($mode = null) {
        if (!empty($mode)) {
            switch ($mode) {
                case Slugify::MODEARRAY:
                    $this->mode = $mode;
                    break;
                default:
                    $this->mode = Slugify::MODEICONV;
                    break;
            }
        }
    }

    /**
     * Takes a string and returns a slugified version of it. Slugs only consists of characters, numbers and the dash. They can be used in URLs. 
     * @param  string $string String
     * @return string         Slug
     */
    public function slugify($string, $separator = '-') {

        switch ($this->mode) {
            case Slugify::MODEARRAY:
                return $this->slugifyByArray($string, $separator);
                break;
            default:
                return $this->slugifyIconv($string, $separator);
                break;
        }
    }

    /**
     * taken form doctrine project
     * needs locale to be set: setlocale(LC_ALL, 'de_DE.utf8','de_DE');
     * 
     * caution: iconv doesnt work on all system, then use slugifyArray
     * 
     * @param type $text
     * @return string
     */
    public static function slugifyIconv($text, $separator = '-') {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', $separator, $text);

        // trim
        $text = trim($text, $separator);

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);

        if (empty($text)) {
            return 'n' . $separator . 'a';
        }

        return $text;
    }

    /**
     * taken from laravel project
     * 
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string  $title
     * @param  string  $separator
     * @return string
     */
    public static function slugifyByArray($title, $separator = '-') {

        $title = preg_replace(array_keys(self::$ascii), array_values(self::$ascii), $title);
        
        $title = preg_replace('/[^\x09\x0A\x0D\x20-\x7E]/', '', $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        $title = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', (defined('MB_CASE_LOWER')) ? mb_strtolower($title) : strtolower($title));

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);


        return trim($title, $separator);
    }

}
