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

/**
 * Slugify
 *
 * @package   org.cocur.slugify
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @author    Ivo Bathke <ivo.bathke@gmail.com>
 * @author    Marchenko Alexandr
 * @copyright 2012-2014 Florian Eckerstorfer
 * @license   http://www.opensource.org/licenses/MIT The MIT License
 */
class Slugify implements SlugifyInterface
{
    /** @var array */
    private $rules = array(
        // Numeric characters
        '¹' => 1,
        '²' => 2,
        '³' => 3,

        // Latin
        'º' => 0,
        '°' => 0,
        'æ' => 'ae',
        'ǽ' => 'ae',
        'À' => 'A',
        'Á' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Å' => 'A',
        'Ǻ' => 'A',
        'Ă' => 'A',
        'Ǎ' => 'A',
        'Æ' => 'AE',
        'Ǽ' => 'AE',
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'å' => 'a',
        'ǻ' => 'a',
        'ă' => 'a',
        'ǎ' => 'a',
        'ª' => 'a',
        '@' => 'at',
        'Ĉ' => 'C',
        'Ċ' => 'C',
        'ĉ' => 'c',
        'ċ' => 'c',
        '©' => 'c',
        'Ð' => 'Dj',
        'Đ' => 'Dj',
        'ð' => 'dj',
        'đ' => 'dj',
        'È' => 'E',
        'É' => 'E',
        'Ê' => 'E',
        'Ë' => 'E',
        'Ĕ' => 'E',
        'Ė' => 'E',
        'è' => 'e',
        'é' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'ĕ' => 'e',
        'ė' => 'e',
        'ƒ' => 'f',
        'Ĝ' => 'G',
        'Ġ' => 'G',
        'ĝ' => 'g',
        'ġ' => 'g',
        'Ĥ' => 'H',
        'Ħ' => 'H',
        'ĥ' => 'h',
        'ħ' => 'h',
        'Ì' => 'I',
        'Í' => 'I',
        'Î' => 'I',
        'Ï' => 'I',
        'Ĩ' => 'I',
        'Ĭ' => 'I',
        'Ǐ' => 'I',
        'Į' => 'I',
        'Ĳ' => 'IJ',
        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ĩ' => 'i',
        'ĭ' => 'i',
        'ǐ' => 'i',
        'į' => 'i',
        'ĳ' => 'ij',
        'Ĵ' => 'J',
        'ĵ' => 'j',
        'Ĺ' => 'L',
        'Ľ' => 'L',
        'Ŀ' => 'L',
        'ĺ' => 'l',
        'ľ' => 'l',
        'ŀ' => 'l',
        'Ñ' => 'N',
        'ñ' => 'n',
        'ŉ' => 'n',
        'Ò' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ō' => 'O',
        'Ŏ' => 'O',
        'Ǒ' => 'O',
        'Ő' => 'O',
        'Ơ' => 'O',
        'Ø' => 'O',
        'Ǿ' => 'O',
        'Œ' => 'OE',
        'ò' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ō' => 'o',
        'ŏ' => 'o',
        'ǒ' => 'o',
        'ő' => 'o',
        'ơ' => 'o',
        'ø' => 'o',
        'ǿ' => 'o',
        'º' => 'o',
        'œ' => 'oe',
        'Ŕ' => 'R',
        'Ŗ' => 'R',
        'ŕ' => 'r',
        'ŗ' => 'r',
        'Ŝ' => 'S',
        'Ș' => 'S',
        'ŝ' => 's',
        'ș' => 's',
        'ſ' => 's',
        'Ţ' => 'T',
        'Ț' => 'T',
        'Ŧ' => 'T',
        'Þ' => 'TH',
        'ţ' => 't',
        'ț' => 't',
        'ŧ' => 't',
        'þ' => 'th',
        'Ù' => 'U',
        'Ú' => 'U',
        'Û' => 'U',
        'Ũ' => 'U',
        'Ŭ' => 'U',
        'Ű' => 'U',
        'Ų' => 'U',
        'Ư' => 'U',
        'Ǔ' => 'U',
        'Ǖ' => 'U',
        'Ǘ' => 'U',
        'Ǚ' => 'U',
        'Ǜ' => 'U',
        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ũ' => 'u',
        'ŭ' => 'u',
        'ű' => 'u',
        'ų' => 'u',
        'ư' => 'u',
        'ǔ' => 'u',
        'ǖ' => 'u',
        'ǘ' => 'u',
        'ǚ' => 'u',
        'ǜ' => 'u',
        'Ŵ' => 'W',
        'ŵ' => 'w',
        'Ý' => 'Y',
        'Ÿ' => 'Y',
        'Ŷ' => 'Y',
        'ý' => 'y',
        'ÿ' => 'y',
        'ŷ' => 'y',

        // Russian
        'Ъ' => '',
        'Ь' => '',
        'А' => 'A',
        'Б' => 'B',
        'Ц' => 'C',
        'Ч' => 'Ch',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'E',
        'Э' => 'E',
        'Ф' => 'F',
        'Г' => 'G',
        'Х' => 'H',
        'И' => 'I',
        'Й' => 'J',
        'Я' => 'Ja',
        'Ю' => 'Ju',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Ш' => 'Sh',
        'Щ' => 'Shch',
        'Т' => 'T',
        'У' => 'U',
        'В' => 'V',
        'Ы' => 'Y',
        'З' => 'Z',
        'Ж' => 'Zh',
        'ъ' => '',
        'ь' => '',
        'а' => 'a',
        'б' => 'b',
        'ц' => 'c',
        'ч' => 'ch',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'e',
        'э' => 'e',
        'ф' => 'f',
        'г' => 'g',
        'х' => 'h',
        'и' => 'i',
        'й' => 'j',
        'я' => 'ja',
        'ю' => 'ju',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'ш' => 'sh',
        'щ' => 'shch',
        'т' => 't',
        'у' => 'u',
        'в' => 'v',
        'ы' => 'y',
        'з' => 'z',
        'ж' => 'zh',

        // German characters
        'Ä' => 'AE',
        'Ö' => 'OE',
        'Ü' => 'UE',
        'ß' => 'ss',
        'ä' => 'ae',
        'ö' => 'oe',
        'ü' => 'ue',

        // Turkish characters
        'Ç' => 'C',
        'Ğ' => 'G',
        'İ' => 'I',
        'Ş' => 'S',
        'ç' => 'c',
        'ğ' => 'g',
        'ı' => 'i',
        'ş' => 's',

        // Latvian
        'Ā' => 'A',
        'Ē' => 'E',
        'Ģ' => 'G',
        'Ī' => 'I',
        'Ķ' => 'K',
        'Ļ' => 'L',
        'Ņ' => 'N',
        'Ū' => 'U',
        'ā' => 'a',
        'ē' => 'e',
        'ģ' => 'g',
        'ī' => 'i',
        'ķ' => 'k',
        'ļ' => 'l',
        'ņ' => 'n',
        'ū' => 'u',

        // Ukrainian
        'Ґ' => 'G',
        'І' => 'I',
        'Ї' => 'Ji',
        'Є' => 'Ye',
        'ґ' => 'g',
        'і' => 'i',
        'ї' => 'ji',
        'є' => 'ye',

        // Czech
        'Č' => 'C',
        'Ď' => 'D',
        'Ě' => 'E',
        'Ň' => 'N',
        'Ř' => 'R',
        'Š' => 'S',
        'Ť' => 'T',
        'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c',
        'ď' => 'd',
        'ě' => 'e',
        'ň' => 'n',
        'ř' => 'r',
        'š' => 's',
        'ť' => 't',
        'ů' => 'u',
        'ž' => 'z',

        // Polish
        'Ą' => 'A',
        'Ć' => 'C',
        'Ę' => 'E',
        'Ł' => 'L',
        'Ń' => 'N',
        'Ó' => 'O',
        'Ś' => 'S',
        'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a',
        'ć' => 'c',
        'ę' => 'e',
        'ł' => 'l',
        'ń' => 'n',
        'ó' => 'o',
        'ś' => 's',
        'ź' => 'z',
        'ż' => 'z',

        // Greek
        'Α' => 'A',
        'Β' => 'B',
        'Γ' => 'G',
        'Δ' => 'D',
        'Ε' => 'E',
        'Ζ' => 'Z',
        'Η' => 'E',
        'Θ' => 'Th',
        'Ι' => 'I',
        'Κ' => 'K',
        'Λ' => 'L',
        'Μ' => 'M',
        'Ν' => 'N',
        'Ξ' => 'X',
        'Ο' => 'O',
        'Π' => 'P',
        'Ρ' => 'R',
        'Σ' => 'S',
        'Τ' => 'T',
        'Υ' => 'Y',
        'Φ' => 'Ph',
        'Χ' => 'Ch',
        'Ψ' => 'Ps',
        'Ω' => 'O',
        'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'ά' => 'a',
        'έ' => 'e',
        'ή' => 'e',
        'ί' => 'i',
        'ΰ' => 'Y',
        'α' => 'a',
        'β' => 'b',
        'γ' => 'g',
        'δ' => 'd',
        'ε' => 'e',
        'ζ' => 'z',
        'η' => 'e',
        'θ' => 'th',
        'ι' => 'i',
        'κ' => 'k',
        'λ' => 'l',
        'μ' => 'm',
        'ν' => 'n',
        'ξ' => 'x',
        'ο' => 'o',
        'π' => 'p',
        'ρ' => 'r',
        'ς' => 's',
        'σ' => 's',
        'τ' => 't',
        'υ' => 'y',
        'φ' => 'ph',
        'χ' => 'ch',
        'ψ' => 'ps',
        'ω' => 'o',
        'ϊ' => 'i',
        'ϋ' => 'y',
        'ό' => 'o',
        'ύ' => 'y',
        'ώ' => 'o',
        'ϐ' => 'b',
        'ϑ' => 'th',
        'ϒ' => 'Y',

        /* Arabic */
        'أ' => 'a',
        'ب' => 'b',
        'ت' => 't',
        'ث' => 'th',
        'ج' => 'g',
        'ح' => 'h',
        'خ' => 'kh',
        'د' => 'd',
        'ذ' => 'th',
        'ر' => 'r',
        'ز' => 'z',
        'س' => 's',
        'ش' => 'sh',
        'ص' => 's',
        'ض' => 'd',
        'ط' => 't',
        'ظ' => 'th',
        'ع' => 'aa',
        'غ' => 'gh',
        'ف' => 'f',
        'ق' => 'k',
        'ك' => 'k',
        'ل' => 'l',
        'م' => 'm',
        'ن' => 'n',
        'ه' => 'h',
        'و' => 'o',
        'ي' => 'y'
    );

    /** @var string[][] */
    private $rulesets = array(
        'esperanto' => array(
            'ĉ' => 'cx',
            'ĝ' => 'gx',
            'ĥ' => 'hx',
            'ĵ' => 'jx',
            'ŝ' => 'sx',
            'ŭ' => 'ux',
            'Ĉ' => 'CX',
            'Ĝ' => 'GX',
            'Ĥ' => 'HX',
            'Ĵ' => 'JX',
            'Ŝ' => 'SX',
            'Ŭ' => 'UX'
        )
    );

    /**
     * @var string[]|null
     */
    private $slugMap;

    /**
     * Returns the slug-version of the string.
     *
     * @param string $string    String to slugify
     * @param string $separator Separator
     *
     * @return string Slug-verison of the string
     */
    public function slugify($string, $separator = '-')
    {
        if($this->slugMap){
            if(isset($this->slugMap[$string])){
                return $this->slugMap[$string];
            }
        }

        $string = strtolower(strtr($string, $this->rules));
        $string = preg_replace('/([^a-z0-9]|-)+/', $separator, $string);
        $string = strtolower($string);

        return trim($string, $separator);
    }

    /**
     * Adds a custom rule to Slugify.
     *
     * @param string $character   Character
     * @param string $replacement Replacement character
     *
     * @return Slugify
     */
    public function addRule($character, $replacement)
    {
        $this->rules[$character] = $replacement;

        return $this;
    }

    /**
     * Adds multiple rules to Slugify.
     *
     * @param string[][] $rules
     */
    public function addRules(array $rules)
    {
        foreach ($rules as $character => $replacement) {
            $this->rules[$character] = $replacement;
        }

        return $this;
    }

    /**
     * Activates an additional ruleset.
     *
     * @param string $name Name of the ruleset.
     *
     * @return Slugify
     *
     * @throws \InvalidArgumentException if the no ruleset with the given name exists
     */
    public function activateRuleset($name)
    {
        if (!isset($this->rulesets[$name])) {
            throw new \InvalidArgumentException('Slugify does not contain a ruleset "'.$name.'".');
        }

        return $this->addRules($this->rulesets[$name]);
    }

    /**
     * Adds a ruleset to the Slugifier.
     *
     * @param string     $name  Name of the ruleset.
     * @param string[][] $rules Rules
     */
    public function addRuleset($name, array $rules)
    {
        $this->rulesets[$name] = $rules;

        return $this;
    }

    /**
     * Returns the rulesets.
     *
     * @return string[][] Rulesets
     */
    public function getRulesets()
    {
        return $this->rulesets;
    }

    /**
     * set a custom map for slugs
     *
     * @param array $slugMap
     */
    public function setSlugMap(array $slugMap){
        $this->slugMap = $slugMap;
    }

    /**
     * add a single slug to the slug map
     *
     * @param $string
     * @param $slug
     */
    public function addSlugToMap($string, $slug){
        $this->slugMap[$string] = $slug;
    }

    /**
     * returns the slug map
     *
     * @return null|string[][] SlugMap
     */
    public function getSlugMap()
    {
        return $this->slugMap;
    }

    /**
     * Static method to create new instance of {@see Slugify}.
     *
     * @return Slugify
     */
    public static function create()
    {
        return new static();
    }
}
