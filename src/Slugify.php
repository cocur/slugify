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
    const LOWERCASE_NUMBERS_DASHES = '/([^A-Za-z0-9]|-)+/';

    /** @var array */
    protected $rules = array(
        // Numeric characters, superscript
        '°' => 0,
        '¹' => 1,
        '²' => 2,
        '³' => 3,
        '⁴' => 4,
        '⁵' => 5,
        '⁶' => 6,
        '⁷' => 7,
        '⁸' => 8,
        '⁹' => 9,
        // Numeric characters, subscript
        '₀' => 0,
        '₁' => 1,
        '₂' => 2,
        '₃' => 3,
        '₄' => 4,
        '₅' => 5,
        '₆' => 6,
        '₇' => 7,
        '₈' => 8,
        '₉' => 9,

        // Latin
        'æ' => 'ae',
        'ǽ' => 'ae',
        'À' => 'A',
        'Á' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Å' => 'AA',
        'Ǻ' => 'A',
        'Ă' => 'A',
        'Ǎ' => 'A',
        'Æ' => 'AE',
        'Ǽ' => 'AE',
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'å' => 'aa',
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
        'Đ' => 'D',
        'ð' => 'dj',
        'đ' => 'd',
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
        'Ø' => 'OE',
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
        'ø' => 'oe',
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
        'Й' => 'Y',
        'Я' => 'Ya',
        'Ю' => 'Yu',
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
        'й' => 'y',
        'я' => 'ya',
        'ю' => 'yu',
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
        'ΑΥ' => 'AU',
        'Αυ' => 'Au',
        'ΟΥ' => 'OU',
        'Ου' => 'Ou',
        'ΕΥ' => 'EU',
        'Ευ' => 'Eu',
        'ΕΙ' => 'I',
        'Ει' => 'I',
        'ΟΙ' => 'I',
        'Οι' => 'I',
        'ΥΙ' => 'I',
        'Υι' => 'I',
        'ΑΎ' => 'AU',
        'Αύ' => 'Au',
        'ΟΎ' => 'OU',
        'Ού' => 'Ou',
        'ΕΎ' => 'EU',
        'Εύ' => 'Eu',
        'ΕΊ' => 'I',
        'Εί' => 'I',
        'ΟΊ' => 'I',
        'Οί' => 'I',
        'ΎΙ' => 'I',
        'Ύι' => 'I',
        'ΥΊ' => 'I',
        'Υί' => 'I',
        'αυ' => 'au',
        'ου' => 'ou',
        'ευ' => 'eu',
        'ει' => 'i',
        'οι' => 'i',
        'υι' => 'i',
        'αύ' => 'au',
        'ού' => 'ou',
        'εύ' => 'eu',
        'εί' => 'i',
        'οί' => 'i',
        'ύι' => 'i',
        'υί' => 'i',
        'Α' => 'A',
        'Β' => 'V',
        'Γ' => 'G',
        'Δ' => 'D',
        'Ε' => 'E',
        'Ζ' => 'Z',
        'Η' => 'I',
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
        'Υ' => 'I',
        'Φ' => 'F',
        'Χ' => 'Ch',
        'Ψ' => 'Ps',
        'Ω' => 'O',
        'Ά' => 'A',
        'Έ' => 'E',
        'Ή' => 'I',
        'Ί' => 'I',
        'Ό' => 'O',
        'Ύ' => 'I',
        'Ϊ' => 'I',
        'Ϋ' => 'I',
        'ϒ' => 'I',
        'α' => 'a',
        'β' => 'v',
        'γ' => 'g',
        'δ' => 'd',
        'ε' => 'e',
        'ζ' => 'z',
        'η' => 'i',
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
        'υ' => 'i',
        'φ' => 'f',
        'χ' => 'ch',
        'ψ' => 'ps',
        'ω' => 'o',
        'ά' => 'a',
        'έ' => 'e',
        'ή' => 'i',
        'ί' => 'i',
        'ό' => 'o',
        'ύ' => 'i',
        'ϊ' => 'i',
        'ϋ' => 'i',
        'ΰ' => 'i',
        'ώ' => 'o',
        'ϐ' => 'v',
        'ϑ' => 'th',

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
        'ي' => 'y',

        /* Vietnamese */
        'ạ' => 'a',
        'ả' => 'a',
        'ầ' => 'a',
        'ấ' => 'a',
        'ậ' => 'a',
        'ẩ' => 'a',
        'ẫ' => 'a',
        'ằ' => 'a',
        'ắ' => 'a',
        'ặ' => 'a',
        'ẳ' => 'a',
        'ẵ' => 'a',
        'ẹ' => 'e',
        'ẻ' => 'e',
        'ẽ' => 'e',
        'ề' => 'e',
        'ế' => 'e',
        'ệ' => 'e',
        'ể' => 'e',
        'ễ' => 'e',
        'ị' => 'i',
        'ỉ' => 'i',
        'ọ' => 'o',
        'ỏ' => 'o',
        'ồ' => 'o',
        'ố' => 'o',
        'ộ' => 'o',
        'ổ' => 'o',
        'ỗ' => 'o',
        'ờ' => 'o',
        'ớ' => 'o',
        'ợ' => 'o',
        'ở' => 'o',
        'ỡ' => 'o',
        'ụ' => 'u',
        'ủ' => 'u',
        'ừ' => 'u',
        'ứ' => 'u',
        'ự' => 'u',
        'ử' => 'u',
        'ữ' => 'u',
        'ỳ' => 'y',
        'ỵ' => 'y',
        'ỷ' => 'y',
        'ỹ' => 'y',
        'Ạ' => 'A',
        'Ả' => 'A',
        'Ầ' => 'A',
        'Ấ' => 'A',
        'Ậ' => 'A',
        'Ẩ' => 'A',
        'Ẫ' => 'A',
        'Ằ' => 'A',
        'Ắ' => 'A',
        'Ặ' => 'A',
        'Ẳ' => 'A',
        'Ẵ' => 'A',
        'Ẹ' => 'E',
        'Ẻ' => 'E',
        'Ẽ' => 'E',
        'Ề' => 'E',
        'Ế' => 'E',
        'Ệ' => 'E',
        'Ể' => 'E',
        'Ễ' => 'E',
        'Ị' => 'I',
        'Ỉ' => 'I',
        'Ọ' => 'O',
        'Ỏ' => 'O',
        'Ồ' => 'O',
        'Ố' => 'O',
        'Ộ' => 'O',
        'Ổ' => 'O',
        'Ỗ' => 'O',
        'Ờ' => 'O',
        'Ớ' => 'O',
        'Ợ' => 'O',
        'Ở' => 'O',
        'Ỡ' => 'O',
        'Ụ' => 'U',
        'Ủ' => 'U',
        'Ừ' => 'U',
        'Ứ' => 'U',
        'Ự' => 'U',
        'Ử' => 'U',
        'Ữ' => 'U',
        'Ỳ' => 'Y',
        'Ỵ' => 'Y',
        'Ỷ' => 'Y',
        'Ỹ' => 'Y',

        /* Georgian */
        'ა' => 'a',
        'ბ' => 'b',
        'გ' => 'g',
        'დ' => 'd',
        'ე' => 'e',
        'ვ' => 'v',
        'ზ' => 'z',
        'თ' => 't',
        'ი' => 'i',
        'კ' => 'k',
        'ლ' => 'l',
        'მ' => 'm',
        'ნ' => 'n',
        'ო' => 'o',
        'პ' => 'p',
        'ჟ' => 'zh',
        'რ' => 'r',
        'ს' => 's',
        'ტ' => 't',
        'უ' => 'u',
        'ფ' => 'f',
        'ქ' => 'k',
        'ღ' => 'gh',
        'ყ' => 'q',
        'შ' => 'sh',
        'ჩ' => 'ch',
        'ც' => 'ts',
        'ძ' => 'dz',
        'წ' => 'ts',
        'ჭ' => 'ch',
        'ხ' => 'kh',
        'ჯ' => 'j',
        'ჰ' => 'h',

        // burmese consonants
        'က'     => 'k',
        'ခ'     => 'kh',
        'ဂ'     => 'g',
        'ဃ'     => 'ga',
        'င'     => 'ng',
        'စ'     => 's',
        'ဆ'     => 'sa',
        'ဇ'     => 'z',
        'စျ'    => 'za',
        'ည'     => 'ny',
        'ဋ'     => 't',
        'ဌ'     => 'ta',
        'ဍ'     => 'd',
        'ဎ'     => 'da',
        'ဏ'     => 'na',
        'တ'     => 't',
        'ထ'     => 'ta',
        'ဒ'     => 'd',
        'ဓ'     => 'da',
        'န'     => 'n',
        'ပ'     => 'p',
        'ဖ'     => 'pa',
        'ဗ'     => 'b',
        'ဘ'     => 'ba',
        'မ'     => 'm',
        'ယ'     => 'y',
        'ရ'     => 'ya',
        'လ'     => 'l',
        'ဝ'     => 'w',
        'သ'     => 'th',
        'ဟ'     => 'h',
        'ဠ'     => 'la',
        'အ'     => 'a',
        // consonant character combos
        'ြ'     => 'y',
        'ျ'     => 'ya',
        'ွ'     => 'w',
        'ြွ'    => 'yw',
        'ျွ'    => 'ywa',
        'ှ'     => 'h',
        // independent vowels
        'ဧ'     => 'e',
        '၏'     => '-e',
        'ဣ'     => 'i',
        'ဤ'     => '-i',
        'ဉ'     => 'u',
        'ဦ'     => '-u',
        'ဩ'     => 'aw',
        'သြော'  => 'aw',
        'ဪ'     => 'aw',
        '၍'     => 'ywae',
        '၌'     => 'hnaik',
        // numbers
        '၀'     => '0',
        '၁'     => '1',
        '၂'     => '2',
        '၃'     => '3',
        '၄'     => '4',
        '၅'     => '5',
        '၆'     => '6',
        '၇'     => '7',
        '၈'     => '8',
        '၉'     => '9',
        // virama and tone marks which are silent in transliteration
        '္'     => '',
        '့'     => '',
        'း'     => '',
        // dependent vowels
        'ာ'     => 'a',
        'ါ'     => 'a',
        'ေ'     => 'e',
        'ဲ'     => 'e',
        'ိ'     => 'i',
        'ီ'     => 'i',
        'ို'    => 'o',
        'ု'     => 'u',
        'ူ'     => 'u',
        'ေါင်'  => 'aung',
        'ော'    => 'aw',
        'ော်'   => 'aw',
        'ေါ'    => 'aw',
        'ေါ်'   => 'aw',
        '်'     => 'at',
        'က်'    => 'et',
        'ိုက်'  => 'aik',
        'ောက်'  => 'auk',
        'င်'    => 'in',
        'ိုင်'  => 'aing',
        'ောင်'  => 'aung',
        'စ်'    => 'it',
        'ည်'    => 'i',
        'တ်'    => 'at',
        'ိတ်'   => 'eik',
        'ုတ်'   => 'ok',
        'ွတ်'   => 'ut',
        'ေတ်'   => 'it',
        'ဒ်'    => 'd',
        'ိုဒ်'  => 'ok',
        'ုဒ်'   => 'ait',
        'န်'    => 'an',
        'ာန်'   => 'an',
        'ိန်'   => 'ein',
        'ုန်'   => 'on',
        'ွန်'   => 'un',
        'ပ်'    => 'at',
        'ိပ်'   => 'eik',
        'ုပ်'   => 'ok',
        'ွပ်'   => 'ut',
        'န်ုပ်' => 'nub',
        'မ်'    => 'an',
        'ိမ်'   => 'ein',
        'ုမ်'   => 'on',
        'ွမ်'   => 'un',
        'ယ်'    => 'e',
        'ိုလ်'  => 'ol',
        'ဉ်'    => 'in',
        'ံ'     => 'an',
        'ိံ'    => 'ein',
        'ုံ'    => 'on'
    );

    /** @var array<string,string>[] */
    protected $rulesets = array(
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

    /** @var string */
    protected $regExp;

    /** @var array */
    protected $options = array('lowercase' => true);

    /**
     *
     * @param string $regExp
     * @param array  $options
     */
    public function __construct($regExp = null, array $options = array())
    {
        $this->regExp  = $regExp ? $regExp : self::LOWERCASE_NUMBERS_DASHES;
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Returns the slug-version of the string.
     *
     * @param string $string    String to slugify
     * @param string $separator Separator
     *
     * @return string Slugified version of the string
     */
    public function slugify($string, $separator = '-')
    {
        $string = strtr($string, $this->rules);
        if ($this->options['lowercase']) {
            $string = strtolower($string);
        }
        $string = preg_replace($this->regExp, $separator, $string);

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
     * @param array<string,string> $rules
     *
     * @return Slugify
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
     * Adds a ruleset to Slugify.
     *
     * @param string               $name  Name of the ruleset.
     * @param array<string,string> $rules Rules
     *
     * @return Slugify
     */
    public function addRuleset($name, array $rules)
    {
        $this->rulesets[$name] = $rules;

        return $this;
    }

    /**
     * Returns the rulesets.
     *
     * @return array<integer,array<string,string>> Rulesets
     */
    public function getRulesets()
    {
        return $this->rulesets;
    }

    /**
     * Sets the regular expression used to sanitize the slug
     *
     * @param string $regExp
     *
     * @return Slugify
     */
    public function setRegExp($regExp)
    {
        $this->regExp = $regExp;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return Slugify
     */
    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Static method to create new instance of {@see Slugify}.
     *
     * @param string $regExp  The regular expression to be applied to strings when calling slugify
     * @param array  $options
     *
     * @return Slugify
     */
    public static function create($regExp = null, array $options = array())
    {
        return new static($regExp, $options);
    }
}
