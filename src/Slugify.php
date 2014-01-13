<?php
namespace Cocur\Slugify;

class Slugify
{
    const MODEICONV = 'iconv';
    const MODEARRAY = 'array';

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
     * Possible slugify methods
     *
     * @var iconv|array
     */
    protected $mode = Slugify::MODEICONV;

    public function __construct($mode = null)
    {
        if ($mode === Slugify::MODEARRAY || !function_exists('iconv')) {
            $this->mode = $mode;
        }
    }

    public static function create($mode = null)
    {
        return new static($mode);
    }

    /**
     * Takes a string and returns a slugified version of it.
     *
     * Slugs only consists of characters, numbers and the dash. They can be used in URLs.
     *
     * @param  string $string String to slugify
     * @param  string $separator Separator to use for spaces
     *
     * @return string            Slug
     */
    public function slugify($string, $separator = '-')
    {
        if (empty($string)) {
            return '';
        }

        $string = preg_replace(
            '/
                  [\x09\x0A\x0D\x20-\x7E]            # ASCII
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

        if ($this->mode === Slugify::MODEICONV) {
            $string = $this->translitByIconv($string);
        } else {
            $string = $this->translitByArray($string);
        }

        // replace non letter or digits by seperator
        $string = preg_replace('#[^\\pL\d]+#u', $separator, $string);

        $string = trim($string, $separator);

        $string = (defined('MB_CASE_LOWER')) ? mb_strtolower($string) : strtolower($string);

        // remove unwanted characters
        $string = preg_replace('#[^-\w]+#', '', $string);

        return $string;
    }

    /**
     * Taken form doctrine project
     * needs locale to be set for country specific transliteration:
     * setlocale(LC_ALL, 'de_DE.utf8','de_DE');
     *
     * caution: iconv doesnt work on all system, then use translitByArray
     *
     * @param  string $string String to slugify
     *
     * @return string
     */
    public static function translitByIconv($string)
    {
        return iconv('utf-8', 'us-ascii//TRANSLIT', $string);
    }

    /**
     * Transliterate a string with a array map
     *
     * @param  string $string String to slugify
     *
     * @return string
     */
    public static function translitByArray($string)
    {
        return preg_replace(array_keys(self::$ascii), array_values(self::$ascii), $string);
    }
}
