<?php
namespace Cocur\Slugify;

class IconvSlugify implements SlugifyInterface
{
    public $inCharset = 'UTF-8';
    public $outCharset = 'ASCII//TRANSLIT';

    public function slugify($string, $separator = '-')
    {
        $string = @iconv($this->inCharset, $this->outCharset, $string);
        $string = preg_replace('/([^a-z0-9]|-)+/usi', $separator, $string);
        $string = strtolower($string);
        return trim($string, $separator);
    }
}
