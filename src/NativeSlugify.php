<?php
namespace Cocur\Slugify;

class NativeSlugify implements SlugifyInterface
{
    public $rules = 'Any-Latin; Latin-ASCII; NFD; NFC; Lower();';

    public function slugify($string, $separator = '-')
    {
        $string = transliterator_transliterate($this->rules, $string);
        $string = preg_replace('/([^a-z0-9]|-)+/usi', $separator, $string);
        return trim($string, $separator);
    }
}